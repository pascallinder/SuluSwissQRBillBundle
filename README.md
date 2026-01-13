# SuluSwissQRBillBundle

**Sulu bundle that integrates swiss qr bill generation for saved contacts**.
Implementation of the php package [schoero/swissqrbill](https://github.com/schoero/swissqrbill)
## Installation

This bundle requires PHP 8.2 and Sulu 2.6

1. Open a command console, enter your project directory and run:

```console
composer require linderp/sulu-swiss-qr-bill-bundle
```

If you're **not** using Symfony Flex, you'll also need to add the bundle in your `config/bundles.php` file:

```php
return [
    //...
    Linderp\SuluSwissQRBillBundle\SuluSwissQRBillBundle::class => ['all' => true],
];
```

2. Register the new routes by adding the following to your `routes_admin.yaml`:

```yaml
SuluIndexNowBundle:
    resource: "@SuluSwissQRBillBundle/Resources/config/routes_admin.yml"
```
4. Add the file `config/packages/sulu_swiss_qr_bill.yaml` with the following configuration and replace #your key here with your actual key:
```yaml
sulu_swiss_qr_bill:
  iban: 'CH5604835012345678009'
  name: 'Example'
  street: 'Nowherestreet 23'
  postalCode: 9999
  city: 'Somewhere'
``` 
5. Reference the frontend code by adding the following to your `assets/admin/package.json`:

```json
"dependencies": {
    "sulu-swiss-qr-bill-bundle": "file:../../vendor/linderp/sulu-swiss-qr-bill-bundle/src/Resources/js"
}
```

5. Import the frontend code by adding the following to your `assets/admin/app.js`:

```javascript
import "sulu-swiss-qr-bill-bundle";
```

6. Build the admin UI:

```bash
cd assets/admin
npm run build
```
