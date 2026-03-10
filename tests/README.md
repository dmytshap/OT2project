Next steps:
1. Edit tests/Acceptance.suite.yml to set url of your application. Change PhpBrowser to WebDriver to enable browser testing
2. Edit tests/Functional.suite.yml to enable a framework module. Remove this file if you don't use a framework
3. Create your first acceptance tests using ```codecept g:cest Acceptance First```
4. Write first test in tests/Acceptance/FirstCest.php
5. Run tests using: ```php vendor/bin/codecept run```

WebDriveria varten pitää Seleneum olla pyörimässä, en itse saanut dockerin kautta pyörimään joten asensin ```npm install selenium-standalone -g```, ```selenium-standalone install``` ja ```selenium-standalone start```, vaatii koneelle NodeJS:n ja Javan