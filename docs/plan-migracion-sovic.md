# Hoja de Ruta Sovic — plan de reconstrucción

Relevamiento del sistema legacy de Sovic SRL, hallazgos de seguridad y plan de reconstrucción de dominio para front-sovic / back-sovic, a partir de la auditoría de pantallas del panel en producción y la reunión con el cliente.

## Resumen

Sovic SRL es una empresa de representación comercial: vende a una red de clientes minoristas los catálogos de 18 marcas/fabricantes que representa, y cobra comisión cuando esas marcas facturan. El sistema actual (PHP legacy, ~2008–2026) cubre ese ciclo pero está frágil, parcialmente roto y con vulnerabilidades activas. `back-sovic` hoy solo tiene construido el esqueleto de autenticación y permisos (Usuarios, Roles, Permisos, Comercio, Sucursal, TipoUsuario).

## El negocio

Sovic no factura a sus clientes — sus proveedores (las marcas) sí. Esa distinción ordena todo el modelo de datos: el pedido es interno de Sovic, la factura pertenece al proveedor, y la comisión de Sovic depende de la factura, no del pedido.

### Entidades centrales

- **Representación / Proveedor** — cada una de las 18 marcas (Cuyoplacas, Apollo, Radio Victoria, Orbis Mertig, Silvestrin, Titanio, Durafort, Gani, Goldmund, Inmacol, Lagos, Mercator, Metalúrgica Tuyú, Music Co, Someco, Bicicletas Futura, Blanco Bahía, Blanco Nieve). Tiene su propia configuración de descuentos, condición de venta, cuentas bancarias y forma de pago.
- **Producto** — pertenece a un único proveedor (nunca más de uno), agrupado por rubro dentro de esa marca.
- **Cliente** — el comercio minorista. Tiene permisos explícitos por marca: a qué proveedores puede o no comprarle (ej. Barbieri: sí Radio Victoria/TCL, no Orbis).
- **Pedido** — nota de pedido de Sovic al proveedor. Tiene ítems, cantidad pedida, y una cascada de descuentos propia.
- **Entrega** — lo que el proveedor efectivamente despachó, puede ser total, parcial o nula respecto de lo pedido.
- **Factura** — emitida por el proveedor. Referencia el número de pedido de Sovic. Dispara el cálculo de comisión.
- **Comisión** — lo que cobra Sovic, calculada sobre lo que el proveedor factura, no sobre lo pedido.
- **Remesa** — seguimiento de pagos con cheque a proveedores: cliente, N° cheque, vencimiento, importe, recibo, factura aplicada.

## Proceso central: de pedido a comisión

El punto de dolor real no es cargar datos — es la falta de visibilidad entre pedido, entrega, facturación y comisión. Hoy Sovic se entera de que un proveedor facturó porque aparece una línea suelta en un resumen de cuenta, sin ítems ni estado de entrega.

Flujo: **Pedido → Entrega (parcial/total/cero) → Facturación (según lo entregado) → Comisión (disparada solo por la facturación)**. El pedido por sí solo nunca dispara la comisión.

Caso especial a respetar en el modelo: en Orbis, "facturado" no significa entregado — factura interno al recibir el anticipo, y recién cuando entrega hace nota de crédito a esa factura interna y emite la factura real. El estado que reporta cada proveedor no se puede tratar de forma uniforme entre proveedores.

El otro problema, en paralelo: cada proveedor tiene su propio sistema de toma de pedidos (ej. "MoviVenta" de Orbis), con login y código de cliente propios — no el mismo ID que usa Sovic. Hoy cada pedido se carga dos veces: una en el sistema de Sovic, otra a mano en el portal del proveedor.

## Mapa de módulos: legacy → nuevo

### Pedidos
- **Alta** — buscar cliente + marca, cargar productos, cascada de 8 descuentos secuenciales (Comercial, Volumen, Publicidad, Contado, Juego, Ofertas, Extra×2), IVA/IIBB/retención, condición de venta, cuenta bancaria. Permite duplicar pedidos programados y editar ítems. *Por construir.*
- **Nuevos Procesados / Procesados / Liquidados** — mismo formulario de notificación por mail en las tres etapas del flujo de estados; cambia la lógica de backend. *Por construir.*
- **Configuración** — Rep. Cuentas, Descuentos, Desc. Grupo, Forma de pagos, Condición Venta, SMTP, Transportes. *Por construir.*

### Comprobantes
- **Buscador** — filtro por representación, ruta, cliente, fechas, N° comp., pagos/pedido. *Por construir.*
- **Alta de comprobante** — 13 tipos (FAC, NDE, NCR, ANT, BON, NDP, REC, REP, C/S, S/B, SAL, RET, CON). *Bug de render en legacy.*
- **Importar Archivo** — única opción del dropdown. *Por construir.*

### Clientes
- **Grupo de Clientes** — matriz cliente × marca, controla a qué proveedores le puede vender Sovic a cada cliente. *Por construir.*
- **Informes** — filtro de comprobantes por cliente/marca/fecha/tipo. *Por construir.*
- **ABM de cliente** — no existe pantalla propia de alta/edición en el legacy. *Ausente, hay que crearlo.*

### Productos
- **Listar** — listado por representación, rubro, código. *Por construir.*
- **Rubro** — ABM de rubros por marca, con IVA e impuesto interno. *Por construir.*
- **Listas de Precios** — repositorio de archivos (PDF/imagen) por marca y fecha, no datos estructurados. *Por construir.*
- **Exportar / Importar** — CSV masivo hacia la tabla Productos, formato variable por proveedor. *Por construir.*
- **Consultar Producto** — filtro + administrador de imágenes. *Por construir.*

### Parámetros
- **Comprobantes** — ABM de los 13 tipos de comprobante. *Por construir.*
- **Representaciones** — datos de las 18 marcas. *Base existente en back-sovic (modelo Comercio).*
- **Rutas** — zonas de reparto, mayoría NOA (Salta, Jujuy, Tucumán). *Por construir.*
- **Comercios** — ABM de comercios. *Roto en legacy: expone código fuente PHP.*
- **Cuentas** — cuentas bancarias por marca. *Roto en legacy: expone código fuente PHP.*
- **Formas de Pago** — listado de bancos disponibles. *Por construir.*

### Gastos, Remesas
- **Gastos → Listado** — consulta por rango de fechas. Antes generaba el Libro de IVA Compras automáticamente; hoy se hace a mano. *A restaurar.*
- **Remesas** — seguimiento de pagos con cheque a proveedores. *No funciona en legacy.*

## Hallazgos de seguridad

- **Crítico** — Parámetros → Comercios y Cuentas sirven el archivo `.php` sin ejecutar, exponiendo la contraseña real de la base de datos (usuario `root`, host `127.0.0.1`) a cualquiera que visite esas URLs. El mismo código concatena `$_REQUEST` sin escapar en INSERT/UPDATE vía `mysql_query` — inyección SQL confirmada. Acción: rotar la contraseña de la base y dar de baja esos dos endpoints hasta arreglar el handler PHP.
- **Medio** — mojibake recurrente en todo el sitio (`FECHA 1¿½`, `REPRESENTACIÃ¿½N`) — mismatch de charset global, no puntual de una vista.
- **Bajo** — cuentas de correo internas visibles en Configuración → SMTP (`oficina@sovicsrl.com.ar`, etc.) — no son contraseñas, pero es información operativa expuesta a cualquiera con acceso al panel admin.

## El problema de integración por proveedor

No hay una solución única — cada una de las ~18 marcas tiene su propio grado de sistematización. Ya existe un bot que baja facturas de al menos un proveedor (Platinum/TCL), aunque poco confiable; su existencia sugiere que hay algún tipo de acceso (API o portal) que vale la pena explorar formalmente. Para otros proveedores (Orbis) hoy no hay ningún mecanismo automático.

Ganancia concreta y alcanzable sin integración profunda: todas las facturas de los proveedores referencian el número de pedido de Sovic — alcanza para automatizar el matching factura↔pedido con solo un pipeline de ingesta de facturas, sin resolver la integración completa con cada proveedor primero.

## Procesos y cómo se resuelven

### Carga de pedido al proveedor (doble carga)
- **Hoy:** el pedido se carga en el sistema de Sovic y, a mano otra vez, en el portal propio de cada proveedor.
- **Se resuelve:** tabla de mapeo código-cliente por proveedor; clasificar cada proveedor en nivel API / bridge (Excel-CSV) / manual (PDF por mail); el pedido nace una sola vez en Sovic, el nivel del proveedor decide cómo sale; ir subiendo proveedores de manual → bridge → API de a uno.

### Actualización de listas de precio
- **Hoy:** cada proveedor manda un Excel con columnas distintas, se reordena a mano antes de subir.
- **Se resuelve:** plantilla de columnas guardada por proveedor tras la primera importación; importaciones siguientes solo confirman formato; el importador reporta productos nuevos, cambios de precio y bajas.

### Ingesta y conciliación de facturas
- **Hoy:** una factura nueva aparece suelta en un resumen de cuenta, sin ítems ni estado de entrega.
- **Se resuelve:** punto único de ingesta (subida manual o bot); se extrae el número de pedido; matcheo automático contra el pedido abierto; si no matchea, cola de revisión; al conciliar se actualiza cantidad entregada y se dispara la comisión.

### Cálculo de comisión
- **Hoy:** manual, depende de enterarse de la factura por cuenta propia.
- **Se resuelve:** gatillo automático al conciliar factura contra pedido; % desde la configuración de marca/producto; fórmula exacta pendiente de confirmar con el cliente.

### Control de cumplimiento (pedido vs. entregado)
- **Hoy:** sin comparación automática entre lo pedido y lo entregado.
- **Se resuelve:** cada línea de pedido guarda cantidad pedida y cantidad entregada por separado; la entregada se completa con la conciliación de factura/remito; reporte por cliente + producto de todo lo pedido, entregado y nunca pedido.

### Registro de pagos a proveedores (Remesa)
- **Hoy:** pago (cheque + recibo) enviado por mail o correspondencia, sin registro central.
- **Se resuelve:** alta de remesa en el sistema con cliente, N° cheque, vencimiento, importe, recibo; aplicación directa contra una o varias facturas.

### Libro de IVA Compras
- **Hoy:** se armaba automático a partir de los gastos cargados; esa función se perdió, hoy se hace a mano.
- **Se resuelve:** reporte que toma rango de fechas sobre Gastos ya cargados y genera el libro automáticamente.

### Permiso cliente–proveedor al armar un pedido
- **Hoy:** la matriz cliente × marca existe pero es solo informativa, nada impide cargar un producto de un proveedor no habilitado.
- **Se resuelve:** el buscador de productos en Alta de Pedido filtra por la matriz de permisos del cliente seleccionado — la regla se aplica sola.

## Hoja de ruta

### Fase 1 — Reconstrucción limpia, paridad funcional
ABM de Cliente + matriz cliente × proveedor; Producto ligado a un único proveedor con rubro/IVA/impuesto interno; motor de Pedido con cascada de descuentos y condición de venta; línea de detalle con cantidad pedida/entregada para trazabilidad histórica; duplicar pedido; Libro de IVA Compras automático; Remesas.

### Fase 2 — Ingesta de facturas y exploración de integraciones (en paralelo, sin bloquear la Fase 1)
Pipeline de ingesta de facturas que matchee por número de pedido; relevar proveedor por proveedor qué tiene disponible (API, portal, o solo Excel); modelo de estado que no asuma semántica uniforme entre proveedores; configuración de integración por proveedor con credenciales cifradas y mapeo de código de cliente.

## Preguntas abiertas

- Fórmula de comisión: ¿% fijo por proveedor, o varía por producto/cliente?
- Migración de datos: el cliente quiere backup del sistema viejo pero arrancar limpio — ¿qué mínimo de histórico necesita el día uno?
- Listas de precio: ¿importador configurable por proveedor, o seguir reformateando a mano antes de importar?
- Confirmar con el cliente que ya se rotó la contraseña de la base de datos expuesta.
