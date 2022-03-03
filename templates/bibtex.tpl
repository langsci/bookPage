{strip}
    {function name=get_bibtex}
        {if $editors}
            {foreach from=$editors key=id item=editor}
                {if $editor@first}
                    {"@book{ldelim}"}{$editor->getLocalizedFamilyName()|strip|replace:' ':''}{$publication->getData('datePublished')|date_format:"%Y"}{","}{"<br>"}
                    {"editor = "}{ldelim}{$editor->getLocalizedFamilyName()|escape}{", "}{$editor->getLocalizedGivenName()|escape}{" "}
                {elseif $editor@last && $editor@total != 1}
                    {"and "}{$editor->getLocalizedFamilyName()|escape}{", "}{$editor->getLocalizedGivenName()|escape}
                {else}
                    {"and "}{$editor->getLocalizedFamilyName()|escape}{", "}{$editor->getLocalizedGivenName()|escape}{" "}
                {/if}
            {/foreach}
            {rdelim}{",<br>"}
        {else}
            {foreach from=$authors key=id item=author}
                {if $author@first}
                    {"@book{ldelim}"}{$author->getLocalizedFamilyName()|strip|replace:' ':''}{$publication->getData('datePublished')|date_format:"%Y"}{","}{"<br>"}
                    {"author = "}{ldelim}{$author->getLocalizedFamilyName()|escape}{", "}{$author->getLocalizedGivenName()|escape}
                {elseif $author@last && $author@total != 1}
                    {"and "}{$author->getLocalizedFamilyName()|escape}{", "}{$author->getLocalizedGivenName()|escape}
                {else}
                    {"and "}{$author->getLocalizedFamilyName()|escape}{", "}{$author->getLocalizedGivenName()|escape}
                {/if}
            {/foreach}
            {rdelim}{",<br>"}
        {/if}

        {"title = "}{ldelim}
            {capture assign="title"}
                {if $publication->getLocalizedData('prefix')}
                    {if $pubState}
                        {$pubStateLabel|escape}{$publication->getLocalizedData('prefix')|regex_replace:"/Forthcoming: |Superseded: /":""|escape}{" "}
                    {else}
                        {$publication->getLocalizedData('prefix')}{" "}
                    {/if}
                {/if}
                {$publication->getLocalizedData('title')}
            {/capture}
            {$title|regex_replace:"/\W(\b(?![a-z]+\b|[A-Z]+\b)[a-zA-Z]+)\b/m":' {$1}'}{rdelim}{","}{"<br>"}
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
            {"series = "}{ldelim}{$series->getLocalizedData('title')}{rdelim}{","}{"<br>"}
            {if $publication->getLocalizedData('seriesPosition')}
                {"number = "}{ldelim}{$publication->getLocalizedData('seriesPosition')}{rdelim}{","}{"<br>"}
            {/if}
        {/if}
        {"address = "}{ldelim}{$currentPress->getData('location')}{rdelim}{","}{"<br>"}
        {"publisher = "}{ldelim}{$currentPress->getLocalizedData('name')}{rdelim}
        {if $publication->getData('pub-id::doi')}
            {","}{"<br>"}{"doi = "}{ldelim}{$publication->getData('pub-id::doi')}{rdelim}
        {elseif count($publicationFormats)}
            {foreach from=$publicationFormats item="publicationFormat"}
                {if $publicationFormat->getIsApproved()}
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