## 1. Dependência

Usando o <a href="https://getcomposer.org/" target="_blank">composer</a>, execute o comando a seguir para instalar automaticamente `composer.json`:

```shell
composer require gilsonsouza/lara-polices
```

ou manualmente no seu arquivo `composer.json`

```json
{
    "require": {
        "composer require gilsonsouza/lara-polices": "^1.0"
    }
}
```

## 2. Middlewares
Para utilizá-los é necessário registrá-los no seu arquivo app/Http/Kernel.php.

```php
 protected $middleware = [
        // other middleware ommited
    	\LaraPolices\Middlewares\PolicesMiddleware::class,
 ];
```

## 3. Provider (opcional)

Para configurar as mensagens de erro e a pasta onde ficará as Polices de seu projeto é necessário adicionar o aquivo `polices.php` a pasta config do seu projeto. Para isso adicione o seguinte código no fim da seção `providers`

```php
// file START ommited
    'providers' => [
        // other pro````viders ommited
        \LaraPolices\Providers\PolicesServiceProvider::class,
    ],
// file END ommited
```

### 3.1 Publicando o arquivo de configuração (somente se tiver feito o passo 3)

Para publicar o arquivo de configuração padrão que acompanham o package, execute o seguinte comando:

```shell
php artisan vendor:publish  --provider="LaraPolices\Providers\PolicesServiceProvider"
```


## 4 Configurações (somente se tiver feito o passo 3, e 3.1)

Configure o arquivo com as mensagens e paths necessários.

`config/polices.php`

## 5 Criando uma Police

Na criação de uma police, adicione `use LaraPolices\Polices\AbstractPolice;` como uma dependência de sua classe que deve extende-la.
As definições de methodos e validações de uma police são bem abertos, apenas algumas convenções devem ser observadas e respeitadas:

```
O mesmo nome de classe usado no Controller deverá ser o nome da classe da Police.

  Ex.: "PostsController" => "PostsPolice"
```

```
As rotas cobertas pelo middleware de polices devem apontar sempre para um {Controller@action} e não possuir um Closure. Rotas com
 closures na definição não são cobertas pelas Polices.

  Coberto: "Route::get('posts', 'PostsController@index')" => "PostsPolice@index"
  Não Coberto: "Route::get('posts', function () {
    return [];
  )" => "-"
```


```
Os métodos de validação da Police devem ser os mesmos do controller no qual será aplicada. Tais metodos recebem `$request`
 como parâmetro para captura de dados e validações

  Ex.: "PostsController@show" => "PostsPolice@show"
```

Para validação com dados do usuário, você poderá utilizar a variável `$this->user` para capturar o Authenticatable atual no Auth.

Exemplo de criação de uma police.
```
<?php

namespace LaraPolicesTests;

use LaraPolices\Polices\AbstractPolice;

class MockPolice extends AbstractPolice
{
    public function mockTrueMethod($request)
    {
        return (bool) ($request->owner_id == $this->user->group['owner_id'] &&
            $request->owner_type == $this->user->group['owner_type']
        );
    }
}

```

Detalhes da classe Abstract Police.

```
<?php

namespace LaraPolices\Polices;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use LaraPolices\Exceptions\ObjectNotFoundException;

abstract class AbstractPolice
{
    /**
     * Define the Autenticatable Interface
     * @var Authenticatable
     */
    protected $user;

    /**
     * @var array Objects storage
     */
    private $objects = array();

    /**
     * AbstractPolice constructor.
     * @param Request $request
     * @param Authenticatable $user
     */
    public function __construct(Authenticatable $user)
    {
        $this->user = $user;
    }

    /**
     * Store object in police
     *
     * @param mixed $object
     * @return $this
     */
    public function pushObject($object)
    {
        $objectReflection = new \ReflectionClass($object);
        $this->objects[$objectReflection->getShortName()] = $object;

        return $this;
    }

    /**
     * Get object from police
     *
     * @param $name
     * @return mixed
     * @throws ObjectNotFoundException
     */
    public function getObject($name)
    {
        if (!isset($this->objects[$name])) {
            if (class_exists($name)) {
                $this->pushObject(App::make($name));

                return $this->getObject($name);
            }

            throw new ObjectNotFoundException("Object not found.");
        }

        return $this->objects[$name];
    }

    /**
     * Function to call action method to authorize resource permission to user.
     * This function should be return a boolean.
     * @param Request $request Request to validate
     * @param string $actionToValidate Police action to validate
     * @return bool
     */
    public function canMakeAction(Request $request, $actionToValidate)
    {
        return (bool) $this->$actionToValidate($request);
    }
}
```

## Agradecimentos e Contribuições

Fique avontade para sugerir correções, melhorias na documentação ou código ou se preferir realizar um pull-request em nosso código. :-D

#### Colaboradores
Gilson Fernandes Batista de Souza - [Site Pessoal](http://gilson.udisoft.me)