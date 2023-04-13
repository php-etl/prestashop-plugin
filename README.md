# PHP-ETL Prestashop plugin
# Installation
```
composer require php-etl/prestashop-plugin
```
# Usage
## Building an extractor
### All:
```yaml
prestashop:
  client:
    url: 'https://prestashop.example.com'
    api_key: 'abc1234'
  extractor:
    type: 'products'
    method: 'all'
    options: # optional
      display: '[field1,field2,...]' # default: full
      filter:
        id: '[1,10]'
      sort: '[field1_ASC,field2_DESC,field3_ASC]'
      limit: '10'
      id_shop: 1
      id_group_shop: 1
      language: 1
      date: 1 # set "1" when using a date filter. (https://github.com/PrestaShop/PrestaShop/issues/10822#issuecomment-426565679)
      price:
        my_price:
          product_attribute: 25
```

[Read the PrestaShop Documentation about options.](https://devdocs.prestashop-project.org/1.7/webservice/tutorials/advanced-use/additional-list-parameters/)

[Read the PrestaShop documentation for information on how to use the `price` option.](https://devdocs.prestashop-project.org/1.7/webservice/tutorials/advanced-use/specific-price)

## Building a loader

### Create:
```
["product" => ["active" => "1", "quantity" => "5", ... ]]
```
```yaml
prestashop:
  client:
    url: 'https://prestashop.example.com'
    api_key: 'abc1234'
  loader:
    type: 'products'
    method: 'create'
    options: # optional
      id_shop: 1
      id_group_shop: 1
```

### Update:
```
["product" => ["id" => "1", "active" => "1", "quantity" => "5", ... ]]
```
```yaml
prestashop:
  client:
    url: 'https://prestashop.example.com'
    api_key: 'abc1234'
  loader:
    type: 'products'
    method: 'update'
    options: # optional
      id_shop: 1
      id_group_shop: 1
```

### Upsert:
(tries to update, creates if not entity found for the given ID)

```
["product" => ["id" => "1", "active" => "1", "quantity" => "5", ... ]]
```
```yaml
prestashop:
  client:
    url: 'https://prestashop.example.com'
    api_key: 'abc1234'
  loader:
    type: 'products'
    method: 'upsert'
    options: # optional
      id_shop: 1
      id_group_shop: 1
```
