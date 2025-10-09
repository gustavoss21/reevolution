- commit
+ ajustei o construtor de static & criar grafico 
Model

## next
media de status por pontus é inutil
block para indicar uma materia nova
### controles
   tratamento de resposta
   tratamento de erros

### views
ajustar o time da aplicação 
validaçao
adinar um servidor (ngix|xampper) docker

- OBS: APP SEM  ['theme'=> $theme]
$tC = (new ThemeModel($data))->find($data);
        $theme = ConsultService::getThemefullData($tC[0]);