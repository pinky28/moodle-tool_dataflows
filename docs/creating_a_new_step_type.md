# Creating a new step type
### Guidelines

New step types can be created in any plugin. To get dataflows to recognise your step, you need to add it
to a function defined in lib.php in your plugin root directory.

```
function <plugin_name>_dataflow_step_types() {
    return [
        new <your_step_type_class>(),
    ];
}
```

If you are adding the step type to the dataflows plugin, add this to the `tool_dataflows_step_types()` function

## Configuration

Configuration is defined by several functions. You will need to override these in your class.

### Declaring the configurations
form_define_fields - Declares the configurations.

### Defining the form fields
form_add_custom_inputs

### Validating the configuration values.

Validation is done by two functions.

validate_config - Validates the form immediately.

validate_for_run - Validates the configuration for runtime.

## Connection Requirements

## Execute function

The step type must implement the execute() function.

For flow step types, the execute function needs to return the step's output. This value will become the input for the
next step.


## Readers

## Documentation

You should provide the following documentation.
- The step type's name string, defined in the plugins's language file, of the form 'step_name_<step_type>'.
- A description string, defined in the plugins's language file, of the form 'step_type_desc_<step_type>'. This string 
is used to display a brief description of what the step does on the step's page.
- If needed, a more detailed description in a separate markdown file.
- Which configurations can hold expressions.
- Any extra variables that the step type uses.

## Other considerations

- Whether or not your step has a side effect (effects the outside world in some way).
- Avoiding side effects on dry runs.