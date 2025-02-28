# 최근 일어난 사건들

```
#최근 일어난 사건들
#title: <span lang="en" dir="ltr" class="mw-content-ltr">Recent events</span>

SELECT ?event ?eventLabel ?date
WITH {
SELECT DISTINCT ?event ?date
WHERE {

# find events
# P31 : instance of 
# P279 : subclass of
# Q1190554 : occurrence (발생)

?event wdt:P31/wdt:P279* wd:Q1190554.
# with a point in time or start date
# P585 : point in time
# P580 : start time

OPTIONAL { ?event wdt:P585 ?date. }
OPTIONAL { ?event wdt:P580 ?date. }

# but at least one of those
FILTER(BOUND(?date) && DATATYPE(?date) = xsd:dateTime).

# not in the future, and not more than 31 days ago
BIND(NOW() - ?date AS ?distance).
FILTER(0 <= ?distance && ?distance < 31).

# 2025년 2월 1일에 발생한 사건
# BIND(YEAR(?date) AS ?year).
# BIND(MONTH(?date) AS ?month).
# BIND(DAY(?date) AS ?day).
# FILTER(?year = 2025 && ?month = 2 && ?day = 1).
# FILTER(YEAR(?date) = 2025 && MONTH(?date) = 2 && DAY(?date) = 1).

}
LIMIT 150
} AS %i
WHERE {
INCLUDE %i
SERVICE wikibase:label { bd:serviceParam wikibase:language "[AUTO_LANGUAGE],mul,en" . }
}
```

접두사  wd :  <http://www.wikidata.org/entity/>    
접두사  wds :  <http://www.wikidata.org/entity/statement/>  
접두사  wdv :  <http://www.wikidata.org/value/>    
접두사  wdt :  <http://www.wikidata.org/prop/direct/>  
접두사  wikibase :  <http://wikiba.se/ontology#>   
접두사  p :  <http://www.wikidata.org/prop/>   
접두사  ps :  <http://www.wikidata.org/prop/statement/>    
접두사  pq :  <http://www.wikidata.org/prop/qualifier/>    
접두사  rdfs :  <http://www.w3.org/2000/01/rdf-schema#>    
접두사  bd :  <http://www.bigdata.com/rdf#>    

```
# 아래 SELECT 쿼리는 다음을 수행합니다. 
# 모든 항목(?의 주제)과 해당 레이블(?레이블)을 선택합니다. 
# 해당 항목(?의 주제)에 직접적인 속성(wdt:) = P279 <하위 클래스>가 있는 경우 
# 값이 엔티티(wd:) = Q7725634 <문학 작품>인 경우 
# 그리고 선택적으로 영어 레이블을 반환합니다.

SELECT  ?s  ?label  WHERE  { 
  ?s  wdt : P279  wd : Q7725634  . 
  OPTIONAL  { 
     ?s  rdfs : label  ?label  filter  ( lang ( ?label )  =  "en" ). 
   } 
 }
```

```
# wikibase:decodeUri 함수 사용 예 
SELECT  DISTINCT  *  WHERE  { 
  ?el  wdt : P31  wd : Q5 . 
  ?webRaw  schema : about  ?el ; 
    schema : inLanguage  "ru" ; 
    schema : isPartOf  <https://ru.wikipedia.org/> . 
  BIND ( URI ( wikibase : decodeUri ( STR ( ?webRaw )))  AS  ?webHyperlink )  . 
  BIND ( wikibase : decodeUri ( STR ( ?webRaw ))  AS  ?webString )  . 
} 
LIMIT  20
```