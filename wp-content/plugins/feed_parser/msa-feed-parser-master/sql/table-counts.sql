  
SELECT TABLE_NAME, SUM(TABLE_ROWS) 
FROM INFORMATION_SCHEMA.TABLES 
WHERE TABLE_SCHEMA = database()
GROUP BY TABLE_NAME 
 ;