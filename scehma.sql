-- Create a new database if it doesn't exist
CREATE DATABASE IF NOT EXISTS healthcare_applications;

-- Use the newly created database
USE healthcare_applications;

-- Create the applicants table
CREATE TABLE IF NOT EXISTS applicants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    date_of_application DATE NOT NULL,
    years_of_experience INT NOT NULL,
    highest_educ_attainment VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- You can also add indexes for better performance if needed
CREATE INDEX idx_email ON applicants(email);