<?php

function sendWelcomeEmailTo($to, $name){
  $from = "mulatabel2023@gmail.com";
  $subject = "Welcome to AASTU E-learning";
  $currentYear = date("Y");
  
  $message = '
  <!DOCTYPE html>
  <html>
  <head>
      <title>Welcome to [Your E-Learning Website Name]!</title>
      <style>
         .container {
            font-family: Arial, sans-serif;
            color: #333;
        }
        .header {
            background-color: #f4f4f4;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
        }
        .footer {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 0;
            background-color: #007BFF; /* Blue background color */
            color: white; /* White text color */
            text-decoration: none;
            border-radius: 5px;
        }
      </style>
  </head>
  <body>
      <div class="container">
          <div class="header">
              <h1>Welcome to AASTU E-learning!</h1>
          </div>
          <div class="content">
              <p>Dear '. $name .',</p>
              <p>Thank you for registering at AASTU E-learning. We are excited to have you join our community!</p>
              <p>At AASTU E-learning, we aim to provide the best learning experience possible. You can start by exploring our wide range of courses and joining a class that suits your interests.</p>
              <p>If you have any questions or need assistance, feel free to reach out to our support team.</p>
              <p>We look forward to seeing you achieve your learning goals!</p>
              <p>Best regards,<br>AASTU E-learning Team</p>
              <a href="http://localhost:5173" class="button" style="color: white; background-color: #007BFF; text-decoration: none; padding: 10px 20px; border-radius: 5px;">Get Started</a>
          </div>
          <div class="footer">
              <p>&copy; ' . $currentYear . ' AASTU E-learning. All rights reserved.</p>
              <p>If you did not register for this account, please ignore this email or contact our support team.</p>
          </div>
      </div>
  </body>
  </html>';
  
  $headers = array(
    'From' => $from,
    'Reply-To' => $from,
    'X-Mailer' => 'PHP/' . phpversion(),
    'Content-type' => 'text/html; charset=iso-8859-1'
  );
  
  // Format the headers
  $headersString = '';
  foreach ($headers as $key => $value) {
    $headersString .= "$key: $value\r\n";
  }
  
 mail($to, $subject, $message, $headersString);
   
}
