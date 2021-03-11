## What to do for full compatibility with OA 3 schema

### EnumSchema

- add support for int|float|... (only scalar)

### IntegerSchema

- format can be int32/int64
- exclusiveMinimum/exclusiveMaximum is `false` by default

### FloatSchema

- format can be float/number

### StringSchema

- `format` is only hint

### Mixed-type array

```yaml
type: array
items:
  oneOf:
    - type: string
    - type: integer
```

### Refs

?

### Union type

```yaml
oneOf:
  - type: string
  - type: integer
```

### Arrays

#### mixed[] | `array`

```yaml
type: array
items: {}
```
(we now sending `items: ` without empty object)

### Objects

#### Readonly, Writeonly

```yaml
type: object
properties:
  id:
    # Returned by GET, not used in POST/PUT/PATCH
    type: integer
    readOnly: true
  username:
    type: string
  password:
    # Used in POST/PUT/PATCH, not returned by GET
    type: string
    writeOnly: true
```

#### Free-Form Object

if we start using `{}` syntax, maybe move
```yaml
type: object
additionalProperties: true
```
to
```yaml
type: object
additionalProperties: {}
```

`{}` isn't nullable! we must use `{nullable: true}`

#### Number of Properties

```yaml
type: object
minProperties: 2
maxProperties: 10
```





- problem ze pri teto implementadci
