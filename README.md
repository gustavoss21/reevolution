- commit

## next
aproveitamento da semana 
mostra a quantidade estudada;
maior pontuaçao Q3 50% M | 
mais tempo sem estudo Q3 25% T |
mais importante Q3 25% I
M+T+I = 100%



novo evento iniciado: 
semana passa SP
semana atual SA
EVOLUÇÃO DA SEMANA
exe: sp - SA = AP%

gerar os dados dinamicamente sobre prioridade, status, aprovetamento, ...

- verificar quais dados realmente precisa nos topicos home
- verificar se os dados apresentados no home-side estão correstos

no home mostrar em destaque apenas uma tarefa, em seguida
mostrar as categorias já definidas talves de forma compactar
ou do jeito que já está, se compacto definir uma bara de media de pontos quantas atividades dessa categoria foi feito, talvez organizar em um modal bootstrap 

media de status por pontus é inutil

block para indicar uma materia nova

em services verificar a necessidade de status em progresso em todos

- definir como vai ser o home, se vai ter varios eventos, ou se vai tem apenas 3 eventos e o resto com estatisticas

criar uma pagina que contenha todos os assuntos, e tenha
ultimo estudo;
aproveitamento;

### controles
   tratamento de resposta
   tratamento de erros

### views
ajustar o time da aplicação 
validaçao
adinar um servidor (ngix|xampper) docker

- OBS: 
estagio de aprendizagem como vai funcionar

APP SEM  ['theme'=> $theme]
$tC = (new ThemeModel($data))->find($data);
        $theme = ConsultService::getThemefullData($tC[0]);


## documentation
### flashcard
os flashcard são responsaveis por criar uma descrição
funcional e dinâmica, pois, podemos criar relacionamento
entre conteudos de forma objetiva
EXEMPLO:
lorem lorem #duvida(boas praticas do css)
-> UTILIDADE:
   - agendar um dia da semana para tirar duvidas especificas
   - aproveitamento do conteudo

EXEMPLO_2:
//cria um relacionamento entre
lorem lorem ipsom #relação(css flexbox)
-> UTILIDADE:
   - gerar relatorios, diagramas, resumos
   - revisão gerais


requisitos de estudo completo:
apreder a fazer;
exercicios concreto;
pensar e criar abstração com diversidade;
fazer execicios;

# topicos home
### CRONOGRAMA PRINCIPAL
não aceita ativades concluidas
### MAIS TEMPO SEM ESTUDO
atividades com mais tem sem estudo aceita as finalizadas
### MAIS IMPORTANTE
não aceita ativades concluidas

