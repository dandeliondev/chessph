#Chesshermit
##Description

Rating system for chess players. The system is based on the Elo rating system, but with some modifications. The system is designed for a small number of players, and is not suitable for large tournaments.

##Updating the ratings

###Step 1

First backup the database or at least the table then run the code below

```Mysql
UPDATE cph_ratings
SET title_prev = title,standard_prev=standard,rapid_prev=rapid,blitz_prev=blitz,f960_prev=f960
```

###Step 2

Go to RatingController, in the store_ratings() function Set the `$arr_int` array to their respective array column in the rating spreadsheet

###Step 3

Go to RatingController, in the store_ratings() function Set the `$col_` variables to their respective column in the rating spreadsheet

###step 4
run the import in browser

http://localhost:8000/import

###Troubleshooting

To resolve issue with special characters open the csv file with notepad++, encoding->convert to utf8



