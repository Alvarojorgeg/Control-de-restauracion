# Control de restauración

Bienvenido a este proyecto, mi nombre es Álvaro y os presento mi proyecto de gestión de restauración.

# Introducción
Este proyecto es un sistema completo para la gestión de restaurantes, desarrollado principalmente en PHP y Javascript. Podemos controlar diferentes áreas del negocio, desde las mesas hasta la nómina de los empleados y la gestión de facturas.

# Funcionalidades
Gestión de Mesas: Controla el estado de las mesas en tiempo real.

Pedidos y Cocina: Maneja los pedidos de los clientes y su estado en la cocina.

Gestión de Empleados: Administra la información de los empleados, sus días libres y sus nóminas.

Facturación: Gestiona las facturas del restaurante.

Reservas: Permite gestionar las reservas de los clientes.

# Páginas Web
Index.php: Página de inicio con la información básica de los trabajadores.

Cocina.php: Página para la gestión de pedidos en la cocina.

Conexión.php: Archivo de configuración para la conexión a la base de datos.

Configuración.php: Menú de configuración accesible solo para el Gerente.

Contabilidad.php: Gestión de nóminas y facturas, accesible solo para el Gerente.

Detalles_empleados.php: Muestra información detallada de los empleados.

Diaslibres.php: Gestión de los días libres de los empleados.

Empleados.php: Formulario para añadir empleados.

Facturas.php: Visualización y gestión de las facturas generadas.

Generarnomina.php: Generación de nóminas en formato PDF.

Menu.php: Gestión del menú de platos del restaurante.

Mesa.php: Gestión de mesas y pedidos.

Miperfil.php: Página de perfil del empleado.

Modificar_empleado.php: Formulario para modificar la información de los empleados.

Nominas.php: Gestión de las nóminas de los empleados.

Obtener_pedidos.php: Recupera los pedidos en estado "cocinando".

Pedirdia.php: Gestión de solicitudes de días libres.

Reservas.php: Gestión de reservas del restaurante.

Sistema.php: Menú con enlaces a la gestión de mesas y cocina.

# Estilos CSS
Estilo General: Organización de clases y estilos comunes para mantener consistencia visual.

Adaptación a Tablets y Móviles: Optimización de la visualización para dispositivos móviles.

# Conclusión y Agradecimientos
La finalidad de este proyecto es hacer que una persona sin ningún conocimiento de informática pueda gestionar su restaurante de una forma super intuitiva y fácil. Los empleados tendrán un agradable menú en el que podrán gestionar y llevar un control de las mesas y de la cocina. Además, el gerente podrá hacer la contabilidad de todos los empleados, tanto generar facturas, nóminas y muchas funciones más.

Este proyecto lo he podido realizar de una forma eficiente gracias a los conocimientos que he adquirido en diferentes lenguajes (HTML, CSS, SQL, PHP, Javascript…) por mis profesores de mi grado superior, especialmente a Jose Manuel, mi profesor de Implantación de aplicaciones Web.

Espero que esta guía te sea de ayuda para entender y usar mi proyecto. ¡Gracias por tu interés!
# AG Control · Plataforma integral para la gestión de restaurantes

Bienvenido a **AG Control**, un ecosistema digital creado para dirigir la operación completa de un restaurante desde un único panel. Este proyecto combina procesos de sala, cocina, reservas, contabilidad y recursos humanos en un flujo coordinado, demostrando mi capacidad para diseñar soluciones end-to-end que transforman el día a día de un negocio de restauración.

> ✨ *Objetivo profesional:* mostrar cómo integro experiencia de usuario, rigor técnico y visión de negocio para liderar proyectos digitales que convencen a dirección y facilitan el trabajo del equipo.

---

## Tabla de contenidos
1. [Visión general del sistema](#visión-general-del-sistema)
2. [Mapa funcional con capturas](#mapa-funcional-con-capturas)
3. [Módulos operativos](#módulos-operativos)
4. [Gestión de personas y RR. HH.](#gestión-de-personas-y-rr-hh)
5. [Finanzas y reporting](#finanzas-y-reporting)
6. [Arquitectura técnica](#arquitectura-técnica)
7. [Modelo de datos](#modelo-de-datos)
8. [Configuración y despliegue](#configuración-y-despliegue)
9. [Experiencia de usuario y diseño](#experiencia-de-usuario-y-diseño)
10. [Roadmap sugerido](#roadmap-sugerido)
11. [Contacto](#contacto)

---

## Visión general del sistema
- **Tecnologías**: PHP 8, MySQL, HTML5, CSS3 y JavaScript vanilla, con generación de PDF mediante Dompdf.
- **Cobertura funcional**: control de mesas en vivo, tickets de cocina, gestión de pedidos y facturas, reservas, directorio de empleados, nóminas, días libres y personalización de menús.
- **Rol del usuario**: accesos segmentados por categoría (gerente, cocinero, camarero, limpieza) que desbloquean diferentes opciones de navegación.
- **Valor diferencial**: reducción de tareas manuales gracias a automatizaciones (cálculo automático de importes, generación de PDFs, paneles de estado) y una interfaz diseñada para ser comprensible incluso para equipos sin experiencia tecnológica.

---

## Mapa funcional con capturas
<table>
  <tr>
    <td align="center">
      <img src="docs/img/seleccionar-usuario.png" alt="Pantalla de selección de usuario" width="360"><br>
      <sub><strong>Selección de usuario</strong> – Acceso mediante DNI y PIN a partir de <code>index.php</code>.</sub>
    </td>
    <td align="center">
      <img src="docs/img/miperfil.png" alt="Perfil del empleado" width="360"><br>
      <sub><strong>Mi perfil</strong> – Panel personal para fichajes, nóminas y datos del empleado (<code>miperfil.php</code>).</sub>
    </td>
  </tr>
  <tr>
    <td align="center">
      <img src="docs/img/mesas-sistema.png" alt="Menú de sistema" width="360"><br>
      <sub><strong>Menú del sistema</strong> – Puerta de entrada a sala y cocina (<code>sistema.php</code>).</sub>
    </td>
    <td align="center">
      <img src="docs/img/pedir-pedido-mesa.png" alt="Gestión de pedidos" width="360"><br>
      <sub><strong>Pedidos por mesa</strong> – Creación y seguimiento de comandas (<code>mesa.php</code>).</sub>
    </td>
  </tr>
  <tr>
    <td align="center">
      <img src="docs/img/pedidos-realizados-tickers.png" alt="Tickets de cocina" width="360"><br>
      <sub><strong>Monitor de cocina</strong> – Prioriza pedidos y estados en vivo (<code>cocina.php</code>).</sub>
    </td>
    <td align="center">
      <img src="docs/img/menu.png" alt="Gestión del menú" width="360"><br>
      <sub><strong>Menú digital</strong> – Alta y mantenimiento del catálogo de platos (<code>menu.php</code>).</sub>
    </td>
  </tr>
  <tr>
    <td align="center">
      <img src="docs/img/reservas.png" alt="Reservas de clientes" width="360"><br>
      <sub><strong>Reservas</strong> – Registro y control de mesas futuras (<code>reservas.php</code>).</sub>
    </td>
    <td align="center">
      <img src="docs/img/diaslibres.png" alt="Gestión de días libres" width="360"><br>
      <sub><strong>Días libres</strong> – Flujo de solicitud y aprobación (<code>diaslibres.php</code>, <code>pedirdia.php</code>).</sub>
    </td>
  </tr>
</table>

---

## Módulos operativos
### Acceso seguro y perfil del empleado
- **Login simplificado**: selección visual del usuario y autenticación por PIN (hash configurable) usando <code>index.php</code> y <code>conexion.php</code>.
- **Panel personal**: desde <code>miperfil.php</code> cada empleado puede fichar entrada/salida, descargar su nómina en PDF y actualizar el PIN.
- **Control de estado**: la variable <code>trabajando</code> se sincroniza con la base de datos y actualiza iconografía en toda la plataforma.

### Gestión de sala
- **Planificador de mesas dinámico** (<code>mesa.php</code>):
  - Configuración de capacidad por mesa con persistencia en JSON dentro de la tabla <code>configuracion_mesas</code>.
  - Visualización en vivo de camareros/cocineros activos y número total de pedidos abiertos.
  - Interfaz drag-and-drop simplificada para añadir pedidos desde el menú digital.
- **Cobro y tickets**:
  - Cálculo automático del ticket a partir de los platos servidos y registro en la tabla <code>factura</code>.
  - Limpieza de pedidos al cerrar la cuenta para mantener coherencia operativa.

### Cadena de cocina
- **Comandas en tiempo real** (<code>cocina.php</code>): recibe los pedidos creados en sala y permite avanzar su estado (Cocinando → Listo → Servido).
- **AJAX ligero**: peticiones asincrónicas para refrescar datos de mesas sin recargar la página.
- **Dashboard rápido**: filtros por estado y contador de tareas pendientes para priorizar al equipo de cocina.

### Catálogo y upselling
- **Gestión del menú** (<code>menu.php</code>): altas, bajas y edición de platos con campos de categoría, ingredientes, precio e imagen.
- **Sincronización con pedidos**: cada cambio se refleja instantáneamente en la selección de platos de <code>mesa.php</code>.
- **Recetas visuales**: soporte para imágenes por plato alojadas en <code>img/menu</code>.

### Reservas y fidelización
- **Agenda de reservas** (<code>reservas.php</code>):
  - Formulario guiado que controla fechas mínimas, número de comensales y turnos predefinidos.
  - Tabla de seguimiento con capacidad de cancelar reservas y feedback inmediato mediante alertas.
- **Registro de intolerancias**: cada reserva captura información crítica para personalizar el servicio.

---

## Gestión de personas y RR. HH.
- **Directorio completo** (<code>empleados.php</code>, <code>detalles_empleados.php</code>): altas, modificaciones y consulta detallada del personal.
- **Workflow de días libres**:
  - Los empleados solicitan días desde <code>pedirdia.php</code>.
  - El área de RR. HH. revisa y aprueba en <code>diaslibres.php</code>, apoyándose en la tabla <code>dia_libre</code>.
- **Fichajes**: seguimiento de presencia en vivo para dimensionar turnos desde cualquier vista de navegación.
- **Portal del empleado**: descarga centralizada de nóminas individuales con control de permisos.

---

## Finanzas y reporting
- **Generación de nóminas en PDF** (<code>generarnomina.php</code>):
  - Uso de Dompdf con plantillas HTML estilizadas y cálculo automático del total aplicando IRPF, horas y descuentos.
  - Almacenamiento organizado en la carpeta <code>nominas/</code> con nomenclatura por DNI.
- **Gestión de facturas** (<code>facturas.php</code>): listado de tickets emitidos con código único, total y mesa asociada.
- **Contabilidad central** (<code>contabilidad.php</code>): acceso restringido a gerencia para navegar entre nóminas, facturas y reportes.
- **Exportabilidad**: la estructura de datos (MySQL) facilita integraciones posteriores con BI o ERP.

---

## Arquitectura técnica
- **Back-end**: PHP procedural optimizado para despliegues en hosting compartidos. Uso de <code>mysqli</code> con consultas preparadas en operaciones críticas.
- **Front-end**: HTML semántico y CSS modularizado en <code>css/style.css</code>, con animaciones suaves y responsive básico para tablets.
- **Plantillas**: reutilización del componente de navegación y tarjetas mediante clases reutilizables.
- **Servicios externos**: librería Dompdf incluida en <code>AGControl/dompdf</code> para generación de documentos.
- **Seguridad**: validación de sesión en todas las vistas, sanitización de inputs via <code>mysqli_real_escape_string</code> y tokens ocultos en formularios.

---

## Modelo de datos
Principales tablas definidas en <code>basededatos.sql</code>:
- <code>empleados</code>: datos maestros, categoría, horario y estado de fichaje.
- <code>pedido</code> & <code>comida</code>: relación plato/mesa con precios para cálculos automáticos.
- <code>factura</code>: histórico de tickets emitidos con importes finales.
- <code>mesareservada</code> & <code>cliente</code>: reservas futuras y sus comensales asociados.
- <code>dia_libre</code>: gestión de ausencias y vacaciones.
- <code>configuracion_mesas</code>: persistencia de la distribución de sala en formato JSON.

---

## Configuración y despliegue
1. **Requisitos**: PHP ≥ 8, MySQL ≥ 8 y servidor web (Apache recomendado).
2. **Clonar el proyecto** y mover la carpeta `AGControl` al directorio público del servidor.
3. **Configurar la base de datos**:
   - Crear la base `proyecto` en MySQL.
   - Importar `basededatos.sql` para recrear tablas, datos de ejemplo y relaciones.
4. **Actualizar credenciales** en `AGControl/conexion.php` con el host, usuario y contraseña del entorno.
5. **Permisos de archivos**: asegurar escritura en `nominas/` si se desean guardar PDFs generados.
6. **Acceso inicial**: visitar `index.php`, seleccionar un empleado de ejemplo e introducir su PIN (`123456` en los datos de demo).

> 💡 *Buenas prácticas*: habilitar HTTPS, fortalecer el almacenamiento de PIN con hash y añadir roles por perfil en futuros despliegues.

---

## Experiencia de usuario y diseño
- **Identidad visual**: paleta azul/cian con acentos dorados para transmitir tecnología y gastronomía premium.
- **Responsive**: maquetación flexbox y media queries que priorizan uso en tablets dentro del local.
- **UX writing**: botones claros (“Fichar”, “Pedir día libre”, “Añadir reserva”) que guían a usuarios sin formación técnica.
- **Feedback constante**: alertas contextuales en formularios, recargas automáticas tras operaciones críticas y navegación consistente.

---

## Roadmap sugerido
- Autenticación con roles granular basados en JWT u OAuth para operar desde dispositivos personales.
- Dashboard analítico con KPIs (ventas diarias, rotación de mesas, ausentismo) y gráficos dinámicos.
- Integración con TPV y sistemas de pago para cerrar el circuito financiero.
- Notificaciones push/email para confirmaciones de reserva y aprobaciones de días libres.
- Internacionalización (ES/EN) para escalar a cadenas multinacionales.

---

## Contacto
¿Quieres saber más o ver una demo guiada? Escríbeme a **alvaro@gmail.com** o conecta en LinkedIn. Estaré encantado de mostrar cómo AG Control puede adaptarse a cualquier restaurante y, sobre todo, cómo puedo aportar valor a tu equipo.

---

> Gracias por dedicar tiempo a conocer AG Control. Este proyecto es mi carta de presentación como desarrollador capaz de unir tecnología, experiencia de usuario y objetivos de negocio.
