# Login Module

## Description

This is a non-secure login module that can be used for your projects.

The module requires a mysqli database connection.

---

How to use

Create a new instance of the Authenticator class and pass the mysqli object thats connected to your database.
```php
$authenticator = new Authenticator (dbconnection);
 ```

#### Available functions

```php
isLoggedIn() : bool
 ```
 return true if a user is logged in

---

```php
attemptLogin(string $username, string $password, bool $userWantsToBeRemembered)
 ```
 Attempts to login the user with the passed login credentials.


Throws LoginException on failed login


---

```php
attemptLoginWithCookies($cookieUsername, $cookiePassword
 ```
 Attempts to login the user with the passed cookie credentials.

 Throws LoginException on failed login

---

```php
attemptLogout()
 ```
 Attempts to logout the user

---

```php
getCookiePassword(string $cookieUsername) : string
 ```
 returns the saved cookiePassword

---

```php
getCookieDuration(string $cookieUsername) : int
 ```
 returns the saved cookieDuration

---

```php
attemptRegisterUser(string $username, string $password, string $passwordRepeat)
 ```
 Attempts to register a new user

 Throws RegisterException on failed registration

---

```php
getUser() : string
 ```
Returns the logged in users username


