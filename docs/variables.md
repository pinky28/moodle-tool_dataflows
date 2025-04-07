# Variables

A variable value can be embedded with expressions. Anything wrapped inside a '${{' and '}}' will be evaluated
as a Symphony expression. These expressions can include references to other variables.

If a variable references another variable, it forms a dependency. The dependencies form a dependency tree.
This tree cannot be circular. That is, a variable cannot ultimately depend on itself. The dependency tree itself
is a DAG.

Many configuration values can hold expressions. Each step details which config
can contain expressions.