<ul>
    <li>Empty fields will be ignored</li>
    <li>Willdcards * and ? can be used in User name, Email and
        Comments, also in multiple definitions, separated by spaces
        (e.g. 'wyz Max*' in User name will search for user 'wyz' and user names, begining from 'Max'.
        With the same result '~' can be used to deny, e.g. '~alfiest' in comments search all users with comments, except
        containing 'alfiest').
    </li>
    <li>Field "Rating" can be 'Inf' and '---'.</li>
    <li>Subnet mask can be defined as traditional or as CIDR</li>
    <li>For search parameters with multiple text fields the second will be
        ignored unless relevant for the type of search chosen.
    </li>
    <li>'Active only' - search users which currently connected to tracker, 'Disabled IPs' - only for disconnected
        users.
    </li>
    <li>The 'p' columns in the results show partial stats, that is, those
        of the torrents in progress.
    </li>
    <li>History bar displays comments, forum posts, e.g. it is a link to user history.</li>
</ul>