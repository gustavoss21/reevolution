child mudou a forma vazia de [] para undefined

## next

apresetar error ou success

se nao comprir com os dias o score aumentar
e o proveitamento abaixa

tema do topico tem que ser a terceira no form,
pois, caso contrario pode esquecer qual era o
topico 

colocar a opçoa de colocar evento temporario

quando for o ultimo ou unico model form, apresentar o stap icon com
um destaque em vermelho em caso de erros

quando houver uma erro em um input resques, aparecera um error, na hora de corrigir as options aparecera abaixo do error

eu estava criando um topico de assunto novo, me deparei
com a pergunta "estuda o assunto a quanto tempo", sendo,
que tinha acabado de marcar a opção "nao comecei a estuar"

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

- verificar quais dados realmente precisa nos topicos home
- verificar se os dados apresentados no home-side estão correstos

definir um campo chadmado "nivel minimo"
label: qual nivel minimo eu preciso atingir no assunto?

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

criar uma obrigatoriedade para ter o primeiro estudo em 24h o segundo em menos de 7 dias e outro antes do 30 dias

1. quando clicar um vez no botao de fitro, ativa, outra vez, desativa. No block left do accompaniment

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
$tC    = (new ThemeModel($data))->find($data);
$theme = ConsultService::getThemefullData($tC[0]);


## documentatio
# NAVEGAÇÃO
   ### acompanhamento
      * visao geral
      * search
      * categorias
      * evoluçao
      * comparação entre eventos
      * comparação do mesmo evento em relação com o tempo

      - template
      rigth  center                left
            |                    |
      filtro      |                    |
            |                    |
            |                    |
            |                    |
            |                    |
            |                    |
            |                    |
            |                    |
            |                    |

   ### aprofundamento
      * aprofundamento
      * evento específico(salvo em cache do navegador)
      * relacionados
   ### cronograma
      * estagions urgentes
      * topicos urgentes
      * revisao
      * temas urgentes
      * categorias urgentes
      
      * porcentagem de aproveitamento
      - urgencia pode ser definida por algum parametro, tempo, dominio, score
   ### cronograma
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
### Revisao
Quando vejo um exercício ou problema dessa matéria, eu sei como começar?

O que poderia ter feito para melhorar seu desempenho?

Como você avalia seu desempenho neste evento


## Scripts disponíveis - dev: 

npm run dev - Inicia servidor de desenvolvimento
npm run build - Build para produção
npm run type-check - Verifica tipos TypeScript
Próximos passos: 
Abra TYPESCRIPT_SETUP.md para guia completo de uso
Utilize o <script setup lang = "ts"> nos seus componentes
Use o alias @/ para importar do diretório static
Execute npm run type-check para validar tipos antes de fazer build



## healp
- o que signica isss: 
93 packages are looking for funding
  run `npm fund` for details

21 vulnerabilities (3 low, 18 high)

To address issues that do not require attention, run: 
  npm audit fix

To address all issues (including breaking changes), run: 
  npm audit fix --force

Run `npm audit` for details.
**************************