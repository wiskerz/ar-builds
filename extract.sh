curl -s http://atlas-reactor.wikia.com/wiki/$1?action=edit | pcregrep -M '{{Infobox(\n|.)*}}' | php extract.php
