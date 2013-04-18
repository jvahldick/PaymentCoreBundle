PaymentCoreBundle
=================

Symfony2 Bundle :: Administração e interligação de objetos junto ao serviço de pagamento.

### Configuration Reference

Arquivo de configuração padrão.

``` yaml
# app/config.yml
### Coração do pagamento
jhv_payment_core:
    ### Nome da conexão
    connection: "default"
    
    ### Classes para configuração
    classes:
        credit          : "Entity\\Credit"
        debit           : "Entity\\Debit"
        instruction     : "Entity\\PaymentInstruction"
        transaction     : "Transaction"
        payment_manager : "JHV\\Payment\\CoreBundle\\Manager\\PaymentManager"
        result          : "JHV\\Payment\\CoreBundle\\Operator\\Connection\\Result"
        
    ### Ouvintes // Listeners
    listeners:
        operation       : "JHV\\Payment\\CoreBundle\\Listener\\OperationListener"
        transaction     : "JHV\\Payment\\CoreBundle\\Listener\\TransactionListener"
```