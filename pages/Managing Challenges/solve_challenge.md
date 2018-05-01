---
title: Solving a challenge
sidebar: mydoc_sidebar
permalink: solve_challenge.html
summary: Solving a challenge in kurukshetra requires modifying the existing piece of code to patch the vulnerability present in it without modifying its functionality i.e. once the patching is done, functionality should still be retained.
---

Once installation is done, anyone with a Google account can login to the framework (and by default only the first logged in user after the installation is given the admin permissions. All the other users are normal users unless one of the admins manually promotes another user to admin via the framework dashboard.)

### Listing challenges

Once logged in, you will be redirected to **`/challenges/`** where all the challenges are listed. You can sort the challenges based on difficulty (easy/medium/hard) via the buttons present on the page.

Challenges listed will have the necessary information like title, description, type (xss/sqli/csrf/..), language (php/Java/NodeJs), and difficulty. By default, after the installation, only one challenge would be present with the framework. One can always upload new challenges (see [How to write a challenge](/write_challenge.html){:target="_blank"}).

### Solving challenges

Let us now try to solve a challenge. As for the example scenario, we will be solving the default challenge which came along with this framework titled "PHP XSS". Clicking on "Solve Challenge" will redirect to the page where users can edit the challenge source code and submit it back to the framework. Let's try to solve the default challenge:

```php
<?php
class Src {
    function sanitize($user_input) {
        $x = htmlspecialchars(trim($user_input));
        return "<span title='".$x."'>User input added as span title </span>";
    }
}
```

From the very simple example above, we can see that inside the class Src `sanitize()` function is defined in order to HTML encode the user input and return it back to prevent XSS attacks.

The vulnerability in the above code is that the user input is getting reflected back in the HTML response as a part of span title tag. The problem here is that the tag uses single quotes to wrap strings and **`htmlspecialchars`** by default won't encode single quotes. Hence the payload **`' onload=alert(2)`** will break out of the string context and will execute `alert(2)`.


### Patching challenges

In order to solve the above challenge, we should fix the vulnerability but the functionality itself shouldn't be changed. So we should patch the challenge in such a way that functionality is retained but vulnerability is patched.

From the [official PHP documentation](http://php.net/manual/en/function.htmlspecialchars.php){:target="_blank"}, it's documented that in order for the htmlspecialchars() to encode single quotes, an argument namely **`ENT_QUOTES``** should be passed on to the function.

So we can patch the vulnerability by updating the challenge code to include the function argument and submit it back to the framework.

```php
<?php
class Src {
    function sanitize($user_input) {
        $x = htmlspecialchars(trim($user_input), ENT_QUOTES);
        return "<span title='".$x."'>User input added as span title </span>";
    }
}
```

Submitting the above code will solve the challenge. If you submit wrong code, you will see error messages based on the submitted code. Let's say we altered the functionality of the code and submit it back to the framework, it will show us error based on the same. For example, if we simply submit the code back to the framework without any modification, then we will get the following message from the framework:


```
Output:

XSS Triggered. Function sanitize() is vulnerable !
```

Similarly, if we simply submit modify the return value to 1 (so that no matter what the user input is, it will simply return 1 always), then the framework will give a different error: `Functionality of sanitize() is not being retained.`

{% include note.html content="
The error messages can be customized while writing unittests. For more information, refer to [How to write a challenge](/write_challenge.html).
"%}

This is how we can solve a challenge in kurukshetra. You can also read about how to write more advanced challenges, see [How to write a challenge](/write_challenge.html).
