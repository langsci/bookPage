{strip}
    {function name=get_bibtex}
        {if $editors}
            {foreach from=$editors key=id item=editor}
                {if $editor@first}
                    {"@book{ldelim}"}{$editor->getLocalizedFamilyName()|strip|replace:' ':''}{$publication->getData('datePublished')|date_format:"%Y"}{","}{"<br>"}
                    {"editor = "}{ldelim}{$editor->getLocalizedFamilyName()|escape}{", "}{$editor->getLocalizedGivenName()|escape}{" "}
                {elseif $editor@last && $editor@total != 1}
                    {" and "}{$editor->getLocalizedFamilyName()|escape}{", "}{$editor->getLocalizedGivenName()|escape}
                {else}
                    {" and "}{$editor->getLocalizedFamilyName()|escape}{", "}{$editor->getLocalizedGivenName()|escape}{" "}
                {/if}
            {/foreach}
            {rdelim}{",<br>"}
        {else}
            {foreach from=$authors key=id item=author}
                {if $author@first}
                    {"@book{ldelim}"}{$author->getLocalizedFamilyName()|strip|replace:' ':''}{$publication->getData('datePublished')|date_format:"%Y"}{","}{"<br>"}
                    {"author = "}{ldelim}{$author->getLocalizedFamilyName()|escape}{", "}{$author->getLocalizedGivenName()|escape}
                {elseif $author@last && $author@total != 1}
                    {" and "}{$author->getLocalizedFamilyName()|escape}{", "}{$author->getLocalizedGivenName()|escape}
                {else}
                    {" and "}{$author->getLocalizedFamilyName()|escape}{", "}{$author->getLocalizedGivenName()|escape}
                {/if}
            {/foreach}
            {rdelim}{",<br>"}
        {/if}

        {capture assign="regexPattern"}
            {'/((?![a-z\p{Ll}]+|[A-Z]+\b)[a-zA-Z\p{L}]+)/mu'}
        {/capture}
        {"title = "}{ldelim}
            {capture assign="title"}
                {if $publication->getLocalizedData('prefix')}
                    {if $pubState}
                        {$pubStateLabel|escape}{$publication->getLocalizedData('prefix')|regex_replace:"/Forthcoming: |Superseded: /":""|escape}{" "}
                    {else}
                        {$publication->getLocalizedData('prefix')|escape}{" "}
                    {/if}
                {/if}
                {$publication->getLocalizedData('title')|escape}
            {/capture}
            {$title|regex_replace:$regexPattern:'{$1}'}{rdelim}{","}{"<br>"}
        {"subtitle = "}{ldelim}
            {capture assign="subtitle"}
                {$publication->getLocalizedData('subtitle')|escape}
            {/capture}
            {$subtitle|regex_replace:$regexPattern:'{$1}'}{rdelim}{","}{"<br>"}
        {"year = "}{ldelim}
            {if $pubState}
                {if $pubState == $smarty.const.PUB_STATE_FORTHCOMING}
                    {$pubStateLabel|regex_replace:"/: /":""}
                {else}
                    {if $publication->getData('datePublished')}
                        {$publication->getData('datePublished')|date_format:"%Y"}
                    {else}
                    {/if}
                {/if}
            {/if}{rdelim}{","}{"<br>"}
        {if $series}
            {"series = "}{ldelim}{$series->getLocalizedData('title')|escape}{rdelim}{","}{"<br>"}
            {if $publication->getLocalizedData('seriesPosition')}
                {"number = "}{ldelim}{$publication->getLocalizedData('seriesPosition')|escape}{rdelim}{","}{"<br>"}
            {/if}
        {/if}
        {"address = "}{ldelim}{$currentPress->getData('location')|escape}{rdelim}{","}{"<br>"}
        {"publisher = "}{ldelim}{$currentPress->getLocalizedData('name')|escape}{rdelim}
        {if $publication->getData('pub-id::doi')}
            {","}{"<br>"}{"doi = "}{ldelim}{$publication->getData('pub-id::doi')}{rdelim}
        {elseif count($publicationFormats)}
            {foreach from=$publicationFormats item="publicationFormat"}
                {if $publicationFormat->getIsApproved() & $publicationFormat->getLocalizedName() == "PDF"}
                    {foreach from=$pubIdPlugins item=pubIdPlugin}
                        {assign var=pubIdType value=$pubIdPlugin->getPubIdType()}
                        {assign var=storedPubId value=$publicationFormat->getStoredPubId($pubIdType)}
                        {if $pubIdType == 'doi' && $storedPubId}
                            {","}{"<br>"}{"doi = "}{ldelim}{$storedPubId|escape}{rdelim}
                            {break}
                        {/if}
                    {/foreach}
                {/if}
            {/foreach}
        {/if}
        {"<br>"}{rdelim}
    {/function}
{/strip}