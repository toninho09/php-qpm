# PHP Queue Process Manager
Uma biblioteca php para controle de filas de processo

###Introdução

Biblioteca criada para a manipulação de processos em fila, usando filas em tabelas de banco de dados,
é possível obter o retorno do processo, assim como o seu status, podendo lançar processos que não apenas
são executados de forma assíncrona com a thread atual da requisição, mais que também podem ser monitorados.

###Instação

```
composer require molecular/queueprocessmanager dev-master
```

###Requerimentos

* PHP >= 5.4.*

####Uso Básico
O uso se resume em um cliente que coloca processos na fila, da mesma forma tem os consumidores, que 
removem os itens da fila e fazem o seu processamento

#####Código do cliente
```php
        //É criado o manipulador da fila, nesse caso por sql
        $handle = new \PhpQPM\QueueHandle\Sql\SqlQueueHandle();
        
        //Informações para conexões do banco
        $handle->connect('mysql:host=localhost;dbname=QueueManager', 'teste', 'teste');
        
        //Cria um Manager para servir de interface para a fila, usando o manipulador sql criado anteriormente
        $manager = new \PhpQPM\Manager($handle);
        
        $foo = 10;
        
        //É colocado uma processo na fila para ser executado, nesse caso um closure
        //O scope do closure é armazenado na fila junto ao closure para ser executado mais tarde
        //o método putProcessOnQueue retorna um observer da fila
        $process = $manager->putProcessOnQueue(function() use($foo){
           return 10 + $foo;
        });
        
        //aguarda o processo ser finalizado
        //O método update do observer atualiza os dados do objeto com base nos dados da fila
        //Após o consumidor da consumir o processo, o método isFinish retornará true
        while(!$process->update()->isFinish()){sleep(1);};
        
        //O método é executado e pode ser obtido o retorno através do método getReturn
        $bar = $process->getReturn();// 20
```
####Código do consumidor
```php
        //É criado a conexão e o Manager da mesma forma que no cliente
        $handle = new \PhpQPM\QueueHandle\Sql\SqlQueueHandle();
        $handle->connect('mysql:host=localhost;dbname=QueueManager', 'teste', 'teste');
        $manager = new \PhpQPM\Manager($handle);
        //Através do manager pode ser gerado o consumidor da fila
        $worker = $manager->createWorker();
        //O método work cria um loop onde é verificado se existe itens pendentes na fila
        //Caso exista itens pendentes na fila, os mesmo são processados
        $worker->work();
```

Simples. =)

###Caracteristicas

O PHP Queue Process Manager utiliza o [Super_Closure](https://github.com/jeremeamia/super_closure) Para
Serializar os Closure e coloca-los na fila, aceitando varias formas de closure e possuindo recursos para serialização de 
escopo.

Alem dos closures é possível estender a classe \PhpQPM\Process\ProcessQueueable e colocar uma classe que possui 
método run na fila, esse método será executado assim que for obtido da fila, alem do método run 
é possível ter acesso direto ao processo.

####Exemplo ProcessQueueable

```php
        //A Classe deve estender ProcessQueueable para poder ser executada na fila
        class SimpleProcess extends \PhpQPM\Process\ProcessQueueable
        {
            //A classe possui o método abstrato run que deve ser implementado com a lógica do processo
            //A classe também pode conter propriedades que as mesmas são serializados para a fila
            public function run()
            {
                return 10;
            }
        }
        
        $handle = new \PhpQPM\QueueHandle\Sql\SqlQueueHandle();
        $handle->connect('mysql:host=localhost;dbname=QueueManager', 'teste', 'teste');
        $manager = new \PhpQPM\Manager($handle);
        
        //O método putProcessOnQueue do Manager aceita um objeto do tipo ProcessQueueable
        $process = $manager->putProcessOnQueue(new SimpleProcess());
        
        while(!$process->update()->isFinish()){sleep(1);};
        
        $bar = $process->getReturn();// 10
```

###Manipulando o processo

Ao colocar um item na fila, é obtido um observer que pode monitorar o processo na fila, algumas das funções
que esse observer possui é verificar o status e obter o retorno da fila, assim como atualizar o próprio observer

```php
    /* Mesmo Código do exemplo anterior*/
    //...
        
        $process = $manager->putProcessOnQueue(new SimpleProcess());
        
        //Verifica se o processo já foi reservado pela fila
        $process->isReserved();
        
        //Verifica se o processo já foi reservado pela fila, porém o seu processamento ainda não foi concluido
        $process->isRunning();
        
        //Verifica se o processo já foi concluído
        $process->isFinish();
        
        //Verifica se o processo ainda não foi reservado pela fila
        $process->isWait();
        
        //Obtem o retorno do processo
        $process->getReturn();
        
        //Verifica se ocorreu algum erro no processamento
        $process->hasError();
        
        //Obtem o erro no processamento
        $process->getError();
        
        //Atualiza o observer com os dados mais atuais da fila
        $process->update();
        
    //...
    
```