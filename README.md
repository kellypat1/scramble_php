# scramble

Aim of this game is to find the hidden word by rearranging the letters.The main page of the application includes the total score, the number of
games that the user has played, the score of the word, a timer and the ability to use three aids to find the right word (whenever an aid is used 10 points are deducted from the word score). In case the user fails to find the correct word within the available time the screen displays the word and gives the user the ability to play a new game. 

<img src="https://github.com/kellypat1/scramble/blob/main/scramble.gif" width="700">

To enter the administrator page, the user must log in using the username **ADMIN** and password **1234**. After a successful log in, the administrator can add new words to the game, modify or delete the existing ones as well as have access to the total number of players and their score.

<img src="https://github.com/kellypat1/scramble/blob/main/scramble_admin1.gif" width="700">

For this application MySqlDatabase has been used. In order to use it, you have to change the data in **config.php** with the data of your database. The next step is to create two tables. The first one (**users**) includes the columns : **id, usename, password, created_at, appear_time, final_socre**. The second one (**words**) includes the columns : **id, word, score**.

This application is in greek language.
