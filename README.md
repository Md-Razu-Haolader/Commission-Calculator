# Commission-Calculator (SOLID)

> Commission calculation from api using SOLID principles.

### Composer CLI:

```bash
composer update
composer dump-autoload
```

### System run command:

```bash
php index.php input.csv
```

### Unit test command:

```bash
php bin/phpunit --testdox
```

<b>Note:</b> Here input.csv is your csv file path

### Application structure:

1. Application root access file is index.php

2. Commission calculation business logics are written in src\Services\Commission\CommissionCalculator.php file

3. Application services location is src\Services

4. Application repositories location is src\Repositories

5. Application helper functions are written in src\Helper\Common.php file

6. Application common configuration are written in src\config\common.php

7. Application validation and request handler location is src\Request
