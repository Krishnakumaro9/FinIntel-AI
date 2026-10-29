# 🏦 FinIntel AI
### AI-Powered Loan Eligibility Prediction & Financial Analytics Platform

**Machine Learning | Financial Technology | Full-Stack Web Development**

**MCA Academic Project — Chandigarh University | 2025–2027**

---

## 📌 Project Overview

**FinIntel AI** is a machine-learning-integrated financial technology web application designed to provide preliminary loan eligibility predictions based on applicant financial and demographic information.

The system combines **PHP, MySQL, Python, and Scikit-learn** to demonstrate how machine learning can be integrated into a web-based financial decision-support application.

Users can register, log in, submit loan applications, view predicted eligibility outcomes, and access application history through a centralized dashboard.

The project is designed for educational and research purposes and is not a substitute for official financial institution lending decisions.

## 🎯 Project Objectives

- Develop a web-based loan eligibility prediction application.
- Integrate a Python machine learning model with a PHP backend.
- Apply a Decision Tree Classifier to structured applicant information.
- Store and retrieve user and application records using MySQL.
- Provide an accessible dashboard for viewing predictions and application history.
- Explore the potential of financial analytics and explainable AI in future versions.

## ✨ Key Features

| Feature | Description |
|---|---|
| User Authentication | Registration, login, and session-based access |
| Loan Application | Structured collection of applicant information |
| ML Prediction | Preliminary eligibility prediction using a Decision Tree |
| User Dashboard | Overview of application and prediction records |
| Prediction History | Access to previous loan prediction outcomes |
| Analytics | Application-level summary and analytics interface |
| Database Integration | MySQL-based storage and retrieval |

## 🛠️ Technology Stack

| Component | Technologies |
|---|---|
| Frontend | HTML5, CSS3, JavaScript, Bootstrap |
| Backend | PHP |
| Database | MySQL |
| Machine Learning | Python, Scikit-learn |
| Algorithm | Decision Tree Classifier |
| Web Server | Apache |
| Local Development | XAMPP, macOS |
| Version Control | Git, GitHub |

## 🏗️ System Architecture

```text
                  FININTEL AI
                      |
                User Interface
               HTML / Bootstrap
                      |
                  PHP Backend
                      |
          +-----------+-----------+
          |                       |
     MySQL Database         Python ML Model
          |                       |
    User & Application      Decision Tree
         Records              Classifier
          |                       |
          +-----------+-----------+
                      |
              Prediction Result
                      |
                User Dashboard
```

The application uses PHP for server-side processing and database communication, while Python handles machine-learning inference.

## 🤖 Machine Learning Methodology

### Algorithm

**Decision Tree Classifier**

The model processes structured applicant information and generates a preliminary eligibility classification.

### Input Features

| Feature | Description |
|---|---|
| Income | Applicant income |
| Credit Score | Numerical credit score |
| Loan Amount | Requested loan amount |
| Age | Applicant age |
| Employment Type | Salaried or self-employed |

### Prediction Output

- **Eligible**
- **Not Eligible**

### ML Workflow

```text
Applicant Information
         |
         v
Input Validation
         |
         v
Feature Preparation
         |
         v
Employment Encoding
         |
         v
Trained Decision Tree
         |
         v
Eligibility Prediction
         |
         v
Result Display
```

**Model evaluation:** Verified accuracy, precision, recall, F1-score, and confusion-matrix results will be documented after formal evaluation.

## 📂 Project Structure

```text
LoanAssistAI/
|
|-- index.php
|-- register.php
|-- login.php
|-- logout.php
|-- dashboard.php
|-- loan_form.php
|-- history.php
|-- analytics.php
|-- db.php
|
|-- predict.py
|-- train_model.py
|-- loan_dataset.csv
|
|-- database/
|-- README.md
```

The structure above represents the main application components. Generated model files and local configuration files may be excluded from the public repository.

## ⚙️ Local Installation

### Prerequisites

- PHP-compatible Apache server
- MySQL
- Python 3
- XAMPP
- Python packages: pandas, numpy, scikit-learn, joblib

### Installation Steps

**1. Download the project**

Download the repository as a ZIP file from GitHub and extract it.

**2. Set up XAMPP**

Place the project folder inside the XAMPP `htdocs` directory.

**3. Configure the database**

Create a MySQL database named `loanassist_ai` and import the project database schema, if provided.

Configure the database connection in `db.php` for your local environment.

**4. Install Python dependencies**

Install the required Python packages in your Python environment.

**5. Prepare the ML model**

Run `train_model.py` with the required dataset to generate the model artifacts.

**6. Configure Python integration**

Ensure the Python executable path used by PHP matches your local Python installation.

**7. Run the application**

Start Apache and MySQL through XAMPP.

Open:

`http://localhost/LoanAssistAI/`

## 📸 Application Screenshots

Add actual screenshots to a `screenshots/` folder in the repository.

Recommended screenshots:

- Login and Registration
- Loan Application Form
- Eligibility Prediction Result
- Dashboard
- Prediction History
- Analytics Page

## 🧪 Testing and Evaluation

The project is intended to be evaluated through:

- Registration and login testing
- Form validation testing
- Database connectivity testing
- PHP–Python integration testing
- Eligibility prediction testing
- Dashboard and history testing
- Machine learning performance evaluation

Detailed test results should be reported only after execution and verification.

## 🚀 Future Scope — FinIntel 360

**FinIntel 360** is the proposed expansion of FinIntel AI into a more comprehensive financial analytics and decision-support platform.

### Planned Enhancements

**1. Advanced Machine Learning**

Compare Decision Tree, Logistic Regression, and Random Forest models using validated performance metrics.

**2. Explainable AI**

Provide interpretable explanations of prediction outcomes and influential features.

**3. Loan Recommendation Engine**

Explore recommendation methods based on applicant profiles and clearly defined eligibility criteria.

**4. EMI Calculator**

Allow users to estimate monthly installment amounts for hypothetical loan scenarios.

**5. Financial Risk Analytics**

Develop educational debt-to-income and affordability indicators.

**6. Interactive Analytics Dashboard**

Introduce advanced visualizations, filters, and financial trend analysis.

**7. Responsible AI and Fairness**

Investigate dataset quality, model bias, explainability, and privacy-aware financial prediction.

**8. Secure Public Deployment**

Explore production hosting, HTTPS, secure authentication, monitoring, and scalable infrastructure.

These enhancements are part of the proposed roadmap and are not represented as completed functionality.

## 🔐 Security and Privacy

FinIntel AI is currently intended for local academic demonstration.

Before public deployment, the application requires security review, including:

- Secure database configuration
- Prepared SQL statements
- Input validation
- Session protection
- Secrets management
- HTTPS configuration
- Personal data protection
- Security testing

Do not upload private financial information, credentials, or production secrets to the public repository.

## 📚 Academic Information

**Project:** FinIntel AI

**Degree:** Master of Computer Applications (MCA)

**Institution:** Chandigarh University

**Academic Session:** 2025–2027

**Domain:** Machine Learning, Financial Technology, Web Development and Data Analytics

## 👨‍💻 Author

**Krishna Kumar**

MCA Student | Chandigarh University

Interested in Data Analytics, Business Analytics, Machine Learning and Software Development.

## ⚠️ Disclaimer

FinIntel AI is an academic prototype developed for learning, experimentation, and demonstration.

Predictions generated by the application are not official loan approvals, financial advice, or decisions made by regulated financial institutions.

---

**FinIntel AI — Exploring the intersection of Machine Learning, Financial Technology and Data Analytics.**
