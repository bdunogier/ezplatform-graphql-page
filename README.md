# eZ Platform GraphQL page integration

This package integrates the eZ Platform Enterprise page features into the GraphQL API.

## Installation

Add the package to `composer.json`:

```json
{   
    "repositories": [
        {"type": "git", "url": "https://github.com/ezsystems/ezplatform-graphql-page.git"}
    ],
    "require": {
	    "ezsystems/ezplatform-graphql-page": "dev-master"
	}
}
```

Enable the bundle in `app/AppKernel.php`:

```php
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new BD\EzPlatformGraphQLPageBundle\BDEzPlatformGraphQLPageBundle(),
        ];
```

Update the dependencies, and regenerate the graphql schema:

```bash
composer update
php bin/console ezplatform:graphql:generate
```

## Features

### Schema and blocks types

The schema generator will create one type for each defined block type: `EmbedPageBlock`, `HeroPageBlock`... those types have a field for each attribute defined for the block.

### Embed attributes support

Embed block attributes will directly give you access to the embedded object. You can then test its type, and query its properties accordingly:

```
... on EmbedPageBlock {
	contentId {
		... on BlogPostContent {
			title
			_location { urlAliases { path } }
		}
	}
}
```

### Structure

When querying a page field, you will get access to zones, and the zone's blocks. Each block will have its own type, allowing you test for fragments, and choose which properties to query from each block.

## Query example

```
{
  pages {
    landingPage(id: 235) {
      page {
        zones {
          name
          blocks {
            __typename
            ... on EmbedPageBlock {
              contentId {
                ... on BlogPostContent {
                  title
                  _location { urlAliases { path } }
                }
              }
            }
          }
        }
      }
    }
  }
}
```

