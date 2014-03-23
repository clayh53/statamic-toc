## Statamic add-on modifiers : toc

### Description

This modifier takes the content piped to it, scans the content for headings and creates an ordered hierarchical list of links to the content areas for each heading level in the document. 

This modifier then creates a clickable table of contents using this list whose anchor tags link to the specific positions in the content referenced in the table of contents. This table of contents is returned *at the top* of the original content, and the original content is returned below the table of contents with its heading tags modified with ids so the user can click and immediately move the cursor to that position in the document.

### Installation

Drop the folder *toc* in the add-ons folder in your Statamic project. Inside this folder is the modifier mod.toc.php file. 


### Usage

Typical use inside a template might look like this:

```
<article>
	<h1>{{title}}</h1>
	{{content|toc}}
</article>
```	

### What it looks like

Note that Statamic modifiers should have **NO** spaces between before or after the pipe ```'|'```!

Also note that if you are chaining modifiers like "widont", this should come last in the chain. In other words, the tag should look like ```{{content|widont|toc}}```. 









