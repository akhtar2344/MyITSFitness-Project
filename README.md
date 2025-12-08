# **MyITS Fitness**
![Platform](https://img.shields.io/badge/Platform-Web-blue)
![Framework](https://img.shields.io/badge/Framework-Laravel-red)
![Database](https://img.shields.io/badge/Database-MySQL-orange)
![Status](https://img.shields.io/badge/Status-Active-green)

**MyITS Fitness** is a comprehensive web-based platform designed to help ITS students submit and verify their sports activities as part of their **SKEM (Student Extracurricular Credit)** requirements.  

This system replaces the current manual and time-consuming process, such as sending proof through WhatsApp, unclear revision feedback, and not knowing whether the submission has been reviewed by lecturers.

With MyITS Fitness, students can upload their activity evidence, track validation status in real-time, and revise submissions directly based on lecturer feedback. Lecturers also receive a dedicated dashboard to review, comment, and approve submissions efficiently.

## **Table of Contents**
- [Why MyITS Fitness?](#why-myits-fitness)
- [Main Objectives](#main-objectives)
- [Product Features](#product-features)
- [Technology Stack](#technology-stack)
- [System Architecture](#system-architecture)
- [Installation Guide](#installation-guide)
- [Usage Guide](#usage-guide)
- [Benefits](#benefits-of-myits-fitness)
- [Development Team](#development-team-pppl--group-4)

---

## **Why MyITS Fitness?**

| **Current Problems** | **MyITS Fitness Solutions** |
|---------------------|----------------------------|
| Manual file submission via WhatsApp | Centralized web-based submission system |
| No consistent validation standards | Standardized assessment criteria |
| Students can't track submission status | Real-time status tracking dashboard |
| Lecturers review hundreds of files manually | Organized digital review interface |
| Unclear revision feedback | Clear, structured feedback system |
| Time-consuming resubmission process | Quick revision and resubmission workflow |

**MyITS Fitness solves all of these problems through an integrated online system that streamlines the entire SKEM submission and validation process.**

---

## **Main Objectives**

| **Objective** | **Description** | **Target Impact** |
|--------------|----------------|-------------------|
| **Simplify Submission** | Streamline student submission of sports activity evidence | Reduce submission time by 70% |
| **Accelerate Validation** | Help lecturers validate submissions quickly and consistently | Faster review process with standardized criteria |
| **Improve Transparency** | Enhance transparency in SKEM assessment process | Real-time status updates for all stakeholders |
| **Digital Transformation** | Support ITS digital transformation (paperless, secure, integrated) | Modernize university administrative processes |

---

## **Product Features**

### For Students
| **Feature** | **Description** | **User Benefit** |
|-------------|-----------------|------------------|
| **User Authentication** | Secure login using myITS SSO account | Single sign-on convenience with university credentials |
| **Home Dashboard** | Displays SKEM sports progress summary (Accepted, Pending, Need Revision) | Clear overview of submission status and progress |
| **Multiple Submission Support** | Submit multiple activities without starting from scratch | Efficient workflow for active students |
| **Activity Submission Form** | Submit sports activities with category selection (Gym/Running/Soccer/Cycling/Basketball/Other) | Easy categorization and proof upload |
| **Private Comment Access** | View lecturer revisions and feedback clearly | Transparent communication with reviewers |
| **Revision & Resubmission** | Revise submissions based on lecturer feedback without redoing entire form | Quick fixes without starting over |
| **Status Tracking** | Real-time submission status: Pending, Accepted, Rejected, Need Revision | Always know where your submission stands |
| **Cancel Submission** | Cancel submissions before lecturer validation | Flexibility to withdraw incorrect submissions |

### For Lecturers/Reviewers
| **Feature** | **Description** | **User Benefit** |
|-------------|-----------------|------------------|
| **User Authentication** | Secure login using myITS SSO account | Secure access with university credentials |
| **Dashboard Overview** | Summary of student submissions by status | Quick overview of workload and pending reviews |
| **Search & Filter** | Search and filter submissions for review | Efficient navigation through large submission volumes |
| **Submission Review** | View submission details, evidence, and make decisions | Comprehensive review interface with all necessary information |
| **Feedback & Comments** | Provide mandatory comments as revision notes | Clear communication channel with students |
| **Decision Actions** | Direct decision actions: Accept, Reject, or Request Revision | Streamlined approval workflow |

### General System Features
| **Feature** | **Description** | **System Benefit** |
|-------------|-----------------|-------------------|
| **Centralized Data Management** | All data, feedback, and validation stored securely | Single source of truth for all SKEM data |
| **Secure Access** | Only authorized users can access respective data | Data privacy and security compliance |
| **Consistent Interface Design** | UI follows myITS ecosystem standards | Familiar user experience across university systems |
| **Real-Time Updates** | Submission status updates instantly after lecturer action | Immediate feedback and status synchronization |
| **Responsive Design** | Accessible on laptops and mobile devices | Flexibility to access from any device |

---

## **Technology Stack**

| **Category** | **Technology** | **Purpose** |
|--------------|----------------|-------------|
| **Backend Framework** | Laravel 11 | Web application framework with MVC architecture |
| **Database** | MySQL | Relational database for data storage |
| **Frontend Framework** | Tailwind CSS | Utility-first CSS framework for responsive design |
| **Data Visualization** | Chart.js | Interactive charts for dashboard analytics |
| **Development Tools** | Composer, NPM, Vite | Package management and build tools |
| **File Storage** | Laravel Storage | Secure file upload and management system |
| **Authentication** | Laravel Sanctum | API authentication and session management |

---

## **System Architecture**

| **Layer** | **Components** | **Responsibility** |
|-----------|----------------|-------------------|
| **Presentation Layer** | Blade Templates, Tailwind CSS, Chart.js | User interface and user experience |
| **Application Layer** | Laravel Controllers, Middleware | Business logic and request handling |
| **Data Layer** | Eloquent ORM, MySQL Database | Data persistence and relationships |
| **Storage Layer** | Laravel Storage, File System | File and media management |

---

## **Installation Guide**

### Prerequisites
| **Requirement** | **Version** | **Purpose** |
|----------------|-------------|-------------|
| **PHP** | 8.2 or higher | Backend runtime environment |
| **MySQL** | 5.7 or higher | Database server |
| **Composer** | Latest | PHP dependency management |
| **Node.js** | 18.x or higher | Frontend build tools |

### Installation Steps
```bash
# 1. Clone repository
git clone https://github.com/akhtar2344/MyITSFitness-Project.git
cd MyITSFitness-Project

# 2. Install PHP dependencies
composer install

# 3. Install Node.js dependencies
npm install

# 4. Environment setup
cp .env.example .env
php artisan key:generate

# 5. Database setup
php artisan migrate
php artisan db:seed

# 6. Build frontend assets
npm run build

# 7. Start development server
php artisan serve
```

---

## **Usage Guide**

### For Students
| **Step** | **Action** | **Description** |
|----------|------------|----------------|
| 1 | **Login** | Use myITS credentials to access the system |
| 2 | **Dashboard** | View current SKEM progress and submission status |
| 3 | **Submit Activity** | Choose activity type, upload proof, fill details |
| 4 | **Track Status** | Monitor submission progress in real-time |
| 5 | **Handle Feedback** | View comments and revise if needed |

### For Lecturers
| **Step** | **Action** | **Description** |
|----------|------------|----------------|
| 1 | **Login** | Access lecturer dashboard with myITS credentials |
| 2 | **Review Queue** | View pending submissions organized by status |
| 3 | **Evaluate** | Review evidence, provide feedback, make decisions |
| 4 | **Manage** | Accept, reject, or request revisions with comments |

---

## **Benefits of MyITS Fitness**

### For Students
| **Benefit** | **Impact** |
|-------------|------------|
| **Real-time Status Tracking** | No need to ask lecturers about submission status |
| **Clear Revision Feedback** | Transparent and structured feedback for improvements |
| **Multi-device Access** | Fast, easy access from phone or laptop anywhere |
| **Quick Resubmission** | Efficient revision process without starting over |

### For Lecturers
| **Benefit** | **Impact** |
|-------------|------------|
| **Organized Review System** | No need to manually check submissions from multiple sources |
| **Standardized Assessment** | Consistent evaluation criteria with mandatory comments |
| **Historical Data Access** | Organized and secure submission history |
| **Reduced Administrative Load** | Streamlined workflow with automated status updates |

### For the University
| **Benefit** | **Impact** |
|-------------|------------|
| **Paperless Transformation** | Supports sustainable and modern campus operations |
| **Data Quality Improvement** | Enhanced reliability and accuracy of SKEM data |
| **Accreditation Support** | Strengthens digital modernization initiatives |
| **Compliance & Security** | Secure data management following university standards |

---

## **Development Team (PPPL – Group 4)**

| Name | NRP |
|------|-----|
| Akhtar Fattan Widodo | 5026231044 |
| Fezih Suhaimah Jinan | 5026231055 |
| Taffy Nirarale Kamajaya | 5026221047 |
| Ahmad Faiz Ramdhani | 5026231064 |
| Marvello Adipertama | 5026231187 |

---

*MyITS Fitness — making sports simpler, validation faster, and SKEM more transparent.*
