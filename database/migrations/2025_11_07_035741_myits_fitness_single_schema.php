<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // ---------- Extensions ----------
        DB::statement("CREATE EXTENSION IF NOT EXISTS pgcrypto;");

        // ---------- ENUM Types ----------
        DB::statement("DO $$
        BEGIN
          IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'role') THEN
            CREATE TYPE public.role AS ENUM ('Student','Lecturer');
          END IF;
        END$$;");

        DB::statement("DO $$
        BEGIN
          IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'file_type') THEN
            CREATE TYPE public.file_type AS ENUM ('JPG','JPEG','PNG','PDF');
          END IF;
        END$$;");

        DB::statement("DO $$
        BEGIN
          IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'submission_status') THEN
            CREATE TYPE public.submission_status AS ENUM ('Pending','Accepted','Rejected','NeedRevision');
          END IF;
        END$$;");

        DB::statement("DO $$
        BEGIN
          IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'notification_type') THEN
            CREATE TYPE public.notification_type AS ENUM ('InApp','Email');
          END IF;
        END$$;");

        // ---------- Trigger Function ----------
        DB::unprepared(<<<'SQL'
        CREATE OR REPLACE FUNCTION public.set_updated_at() RETURNS trigger
        LANGUAGE plpgsql AS $$
        BEGIN
          NEW.updated_at = now();
          RETURN NEW;
        END;
        $$;
        SQL);

        // ========== TABLES ==========
        // user_account
        Schema::create('user_account', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->text('email')->unique();
            $table->text('password_hash');
            $table->string('role', 20); // temporary; converted to enum below
            $table->boolean('is_active')->default(true);
            $table->timestampTz('last_login_at')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
        });
        DB::statement("ALTER TABLE public.user_account ALTER COLUMN role TYPE public.role USING role::public.role;");

        // session
        Schema::create('session', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('user_id');
            $table->text('token')->unique();
            $table->timestampTz('issued_at')->useCurrent();
            $table->timestampTz('expires_at');
            $table->timestampTz('revoked_at')->nullable();
            $table->timestampTz('last_login_at')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('user_account')->onDelete('cascade');
            $table->index('user_id', 'idx_session_user');
        });

        // student
        Schema::create('student', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('user_id')->unique();
            $table->text('nrp')->unique();
            $table->text('name');
            $table->text('email');
            $table->text('program');
            $table->timestampTz('created_at')->useCurrent();

            $table->foreign('user_id')->references('id')->on('user_account')->onDelete('cascade');
        });

        // lecturer
        Schema::create('lecturer', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('user_id')->unique();
            $table->text('employee_id')->unique();
            $table->text('name');
            $table->text('email');
            $table->text('department');

            $table->foreign('user_id')->references('id')->on('user_account')->onDelete('cascade');
        });

        // activity
        Schema::create('activity', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->text('name');
            $table->date('date');
            $table->text('location');
            $table->integer('duration_minutes');
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
        });
        DB::statement("ALTER TABLE public.activity ADD CONSTRAINT activity_duration_positive CHECK (duration_minutes > 0);");

        // submission
        Schema::create('submission', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('student_id');
            $table->uuid('activity_id');
            $table->string('status', 20); // temporary; converted to enum below
            $table->text('notes')->nullable();
            $table->timestampTz('canceled_at')->nullable();
            $table->integer('duration_minutes');
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();

            $table->foreign('student_id')->references('id')->on('student')->onDelete('cascade');
            $table->foreign('activity_id')->references('id')->on('activity')->onDelete('restrict');

            $table->index('student_id', 'idx_submission_student');
            $table->index('activity_id', 'idx_submission_activity');
        });
        DB::statement("ALTER TABLE public.submission ALTER COLUMN status TYPE public.submission_status USING status::public.submission_status;");
        DB::statement("ALTER TABLE public.submission ALTER COLUMN status SET DEFAULT 'Pending';");
        DB::statement("ALTER TABLE public.submission ADD CONSTRAINT submission_duration_positive CHECK (duration_minutes > 0);");
        DB::statement("CREATE INDEX IF NOT EXISTS idx_submission_status ON public.submission(status);");

        // comment
        Schema::create('comment', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('submission_id');
            $table->text('body');
            $table->boolean('private')->default(false);
            $table->uuid('student_id')->nullable();
            $table->uuid('lecturer_id')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();

            $table->foreign('submission_id')->references('id')->on('submission')->onDelete('cascade');
            $table->foreign('student_id')->references('id')->on('student')->onDelete('set null');
            $table->foreign('lecturer_id')->references('id')->on('lecturer')->onDelete('set null');

            $table->index('submission_id', 'idx_comment_submission');
        });
        DB::statement("
          ALTER TABLE public.comment
          ADD CONSTRAINT comment_role_exclusive
          CHECK (
            (student_id IS NOT NULL AND lecturer_id IS NULL) OR
            (student_id IS NULL AND lecturer_id IS NOT NULL)
          );
        ");

        // file_attachment
        Schema::create('file_attachment', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('submission_id');
            $table->text('file_name');
            $table->string('file_type', 10); // temporary; converted to enum below
            $table->text('url');
            $table->double('size_mb');
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();

            $table->foreign('submission_id')->references('id')->on('submission')->onDelete('cascade');
            $table->index('submission_id', 'idx_file_submission');
        });
        DB::statement("ALTER TABLE public.file_attachment ALTER COLUMN file_type TYPE public.file_type USING file_type::public.file_type;");
        DB::statement("ALTER TABLE public.file_attachment ADD CONSTRAINT chk_file_size CHECK (size_mb >= 0 AND size_mb <= 25);");
        DB::statement("ALTER TABLE public.file_attachment ADD CONSTRAINT chk_file_type_legal CHECK (file_type IN ('JPG','PNG','PDF','JPEG'));");

        // revision_request
        Schema::create('revision_request', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('submission_id');
            $table->uuid('lecturer_id');
            $table->text('message');
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('resolved_at')->nullable();

            $table->foreign('submission_id')->references('id')->on('submission')->onDelete('cascade');
            $table->foreign('lecturer_id')->references('id')->on('lecturer')->onDelete('restrict');

            $table->index('submission_id', 'idx_revision_submission');
        });

        // status_history
        Schema::create('status_history', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('submission_id');
            $table->string('from_status', 20); // temporary; converted to enum below
            $table->string('to_status', 20);   // temporary; converted to enum below
            $table->text('note')->nullable();
            $table->timestampTz('changed_at')->useCurrent();

            $table->foreign('submission_id')->references('id')->on('submission')->onDelete('cascade');
            $table->index('submission_id', 'idx_status_hist_submission');
        });
        DB::statement("ALTER TABLE public.status_history ALTER COLUMN from_status TYPE public.submission_status USING from_status::public.submission_status;");
        DB::statement("ALTER TABLE public.status_history ALTER COLUMN to_status   TYPE public.submission_status USING to_status::public.submission_status;");

        // notification
        Schema::create('notification', function (Blueprint $table) {
            $table->uuid('id')->primary()->default(DB::raw('gen_random_uuid()'));
            $table->uuid('recipient_user_id');
            $table->string('type', 20); // temporary; converted to enum below
            $table->text('title');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->uuid('related_submission_id')->nullable();
            $table->timestampTz('created_at')->useCurrent();

            $table->foreign('recipient_user_id')->references('id')->on('user_account')->onDelete('cascade');
            $table->foreign('related_submission_id')->references('id')->on('submission')->onDelete('cascade');

            $table->index('recipient_user_id', 'idx_notification_recipient');
            $table->index('related_submission_id', 'idx_notification_related');
        });
        DB::statement("ALTER TABLE public.notification ALTER COLUMN type TYPE public.notification_type USING type::public.notification_type;");

        // ========== TRIGGERS ==========
        DB::unprepared("
          DROP TRIGGER IF EXISTS trg_auth_user_account_updated ON public.user_account;
          CREATE TRIGGER trg_auth_user_account_updated
          BEFORE UPDATE ON public.user_account
          FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();
        ");

        DB::unprepared("
          DROP TRIGGER IF EXISTS trg_auth_session_updated ON public.session;
          CREATE TRIGGER trg_auth_session_updated
          BEFORE UPDATE ON public.session
          FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();
        ");

        DB::unprepared("
          DROP TRIGGER IF EXISTS trg_activity_updated ON public.activity;
          CREATE TRIGGER trg_activity_updated
          BEFORE UPDATE ON public.activity
          FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();
        ");

        DB::unprepared("
          DROP TRIGGER IF EXISTS trg_submission_submission_updated ON public.submission;
          CREATE TRIGGER trg_submission_submission_updated
          BEFORE UPDATE ON public.submission
          FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();
        ");

        DB::unprepared("
          DROP TRIGGER IF EXISTS trg_comment_updated ON public.comment;
          CREATE TRIGGER trg_comment_updated
          BEFORE UPDATE ON public.comment
          FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();
        ");

        DB::unprepared("
          DROP TRIGGER IF EXISTS trg_file_attachment_updated ON public.file_attachment;
          CREATE TRIGGER trg_file_attachment_updated
          BEFORE UPDATE ON public.file_attachment
          FOR EACH ROW EXECUTE FUNCTION public.set_updated_at();
        ");
    }

    public function down(): void
    {
        // Drop triggers
        DB::unprepared("DROP TRIGGER IF EXISTS trg_file_attachment_updated ON public.file_attachment;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_comment_updated ON public.comment;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_submission_submission_updated ON public.submission;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_activity_updated ON public.activity;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_auth_session_updated ON public.session;");
        DB::unprepared("DROP TRIGGER IF EXISTS trg_auth_user_account_updated ON public.user_account;");

        // Drop tables (reverse order)
        Schema::dropIfExists('notification');
        Schema::dropIfExists('status_history');
        Schema::dropIfExists('revision_request');
        Schema::dropIfExists('file_attachment');
        Schema::dropIfExists('comment');
        Schema::dropIfExists('submission');
        Schema::dropIfExists('activity');
        Schema::dropIfExists('lecturer');
        Schema::dropIfExists('student');
        Schema::dropIfExists('session');
        Schema::dropIfExists('user_account');

        // Drop function
        DB::unprepared("DROP FUNCTION IF EXISTS public.set_updated_at();");

        // Drop ENUM types (reverse of creation)
        DB::statement("DO $$
        BEGIN
          IF EXISTS (SELECT 1 FROM pg_type WHERE typname = 'notification_type') THEN
            DROP TYPE public.notification_type;
          END IF;
        END$$;");

        DB::statement("DO $$
        BEGIN
          IF EXISTS (SELECT 1 FROM pg_type WHERE typname = 'submission_status') THEN
            DROP TYPE public.submission_status;
          END IF;
        END$$;");

        DB::statement("DO $$
        BEGIN
          IF EXISTS (SELECT 1 FROM pg_type WHERE typname = 'file_type') THEN
            DROP TYPE public.file_type;
          END IF;
        END$$;");

        DB::statement("DO $$
        BEGIN
          IF EXISTS (SELECT 1 FROM pg_type WHERE typname = 'role') THEN
            DROP TYPE public.role;
          END IF;
        END$$;");
    }
};
