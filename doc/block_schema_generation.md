For each defined page block, a GraphQL type is generated.

```yaml
EmbedPageBlock:
    type: object
    inherits: [BasePageBlock]
    config:
        interfaces: [PageBlock]
        fields:
            # @todo How do we get from "contentId" to "content" when generating ? Do we want to ?
            content:
                type: DomainContent
                # @todo implement me
                resolve: "@=resolver('ContentById', [value.getAttribute('contentId').getValue()])"
            html:
                type: String
                args:
                    view:
                        type: EmbedPageBlockViews

EmbedPageBlockViews:
    type: enum
    config:
        values:
            default:
                description: "Default block layout"
                value: "EzPlatformPageFieldTypeBundle:blocks:embed.html.twig"
            embed:
                value: "@ezdesign/blocks/embed.html.twig"
                description: "Platform EE Demo embed block"
            embed_wide:
                value: "@ezdesign/blocks/embed_wide.html.twig"
                description: "Platform EE Demo wide embed block"
```