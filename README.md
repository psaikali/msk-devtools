# Dev tools by [Mosaika](https://mosaika.fr)

**A collection of useful functions used when developing WordPress themes and plugins.**

Feel free to use and contribute!

## Utility functions

#### Write in debug log
```MSK::debug( [ 'something' => 12 ] )```

Write something (string, object, array... anything!) in `wp-content/debug.log`

---

#### Pretty print
```MSK::pp( [ 'something' => 12 ] )```

Debug something (string, object, array... anything!) on screen.

---

#### Inspect hooks
```MSK::inspect_hooks( [ 'woocommerce', 'order' ] );```

List all the functions (and their file location) triggered by specific hooks.
If you want to inspect all the hooks containing multiple words, pass an array. The example above will list all functions hooked to a hook that contains `woocommerce` AND `order` in its name.

---

#### Remove unattainable hook
```MSK::remove_class_hook( 'wp_some_hook_tag', 'ClassName', 'method_function_name' );```

Remove an "unattainable hook" registered within a class where it's impossible to access the instance of this class. Credits to [fuxia](https://wordpress.stackexchange.com/a/57088).