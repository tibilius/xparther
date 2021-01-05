Simple service for parsing sites with xpath, u can simulate some clicks before parsing. Also service can create screenshots during clicks (and on open site).

endpoints:
 
*  `/api/parse`
   
    request params:
    *  `url` - site url,      
    *  `script` - optional, js script for evaluate after opening,
    *  `clicks` - optional, array of element xpath, which need to be clicked        
    *  `items` -  array of parsed items, struct is:    
        *   `xpath` -  xpath of item,        
        *   `name` - name in result array,
    
    *   `debug`, optional, if need screenshots


*  `/ping`
    response is always `pong` 