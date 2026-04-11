-- Add payment_method column to bookings table if it doesn't exist
-- Run this SQL script in your database

ALTER TABLE `bookings` 
ADD COLUMN `payment_method` VARCHAR(20) DEFAULT 'cash' AFTER `total money`;

-- Update existing records to have a default payment method
UPDATE `bookings` 
SET `payment_method` = 'cash' 
WHERE `payment_method` IS NULL;
