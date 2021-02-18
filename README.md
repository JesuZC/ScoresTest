# Welcome to ScoresTest!

Hi! This project is a litle test for read .CSV files in Symfony.
### Requeriments
- PHP v7.4^.
- Symfony CLI.
- Virtual Host (optional).

## INSTALL

Just clone this repository and then do:
>composer install

>composer dump-autoload

## Test
You should use the symfony CLI:
>symfony server:start

After that go to your mounted virtual server. You will see 6 table tests from one .CSV file already on repository.

### Preload Tests
- For scores between 20-50
- For scores between -40-0
- For scores between 0-80


- For Rows with Region: 'CA', Gender: 'w', without Legal Age and with negative Score.
- For Rows with Region: 'CA', Gender: 'w', without Legal Age and with positive Score.
- For Rows with Region: 'CA', Gender: 'w', with Legal Age and with positive Score.

### OneFile

On that folder you could find a test from same .CSV file doing with native PHP.
For testing it you need to mount a virtual host on your local machine.
## Thanks for watching!!
