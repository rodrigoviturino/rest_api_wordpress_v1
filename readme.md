# JWT

- Gerando TOKEN
  - é necessário informar no body da requisição as seguintes informações, como passamos no arquivo usuario_post
  - { "username" : "usuario", "password" : "senha" }
  - ao inserir os dados, precisa informar na URL o seguinte endereço: /wp-json/jwt-auth/v1/token e logo vai gerar o token.


{
"email" : "rodrigo1@outlook.com",
"senha" : "123",
"nome" : "Rodrigo",
"rua" : "Viela 19",
"cep" : "0000000",
"numero" : "30",
"bairro" : "Guaianases",
"cidade" : "SP",
"estado" : "SP"
}