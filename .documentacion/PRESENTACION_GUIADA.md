# Guía de Presentación: ReservaYa (13 Diapositivas)

Esta es la guía definitiva para tu defensa, adaptada a tu estilo y con los cambios que me has pedido.

---

## Diapositiva 1: Portada
- **Título**: ReservaYa: Gestión de Citas Online
- **Subtítulo**: Proyecto Final de Samuel Ramirez Reina
- **Nota**: He quitado lo de microservicios y seguridad profesional para que sea más directo y sencillo.

---

## Diapositiva 2: ¿Por qué este proyecto? (Motivación)
- **Texto**: "He hecho este proyecto porque me parecía muy útil para digitalizar negocios tradicionales, como una peluquería. Antes se hacía todo con papel y boli, y con esta web el cliente puede reservar desde su móvil a cualquier hora sin tener que llamar."
- **Nota**: He quitado las frases rebuscadas como "modernizando la operativa".

---

## Diapositiva 3: Lo que he aplicado de clase
- **Texto**: "Este proyecto es el resumen de lo que hemos visto en el ciclo. He aplicado conocimientos de **Sistemas Operativos** (configurando el servidor Linux), **Virtualización** (usando Docker para los contenedores) y **Desarrollo Web** (creando toda la lógica en PHP)."

---

## Diapositiva 4: Tecnologías elegidas
- **Texto**: "He elegido PHP, MySQL y Docker básicamente porque no he querido complicarme la vida y he preferido usar las herramientas que hemos dado en clase, que son las que mejor conozco y sé que funcionan bien juntas."

---

## Diapositiva 5: La Base de Datos (MySQL)
- **Texto**: "He usado MySQL para guardar toda la información. Tengo tablas para los usuarios y para las citas. Me he asegurado de que sea una base de datos sólida para que no se pierda ninguna reserva."

---

## Diapositiva 6: Diseño y Estética
- **Texto**: "Quería que la web fuera moderna. He usado un estilo llamado 'Glassmorphism' (efecto cristal) con CSS para que sea visualmente atractiva y fácil de usar para cualquier cliente."

---

## Diapositiva 7: Seguridad de los Datos
- **Texto**: "Aunque sea un proyecto de clase, la seguridad importa. Las contraseñas no se guardan como texto normal, sino que están encriptadas. Además, he usado 'sentencias preparadas' para que nadie pueda hackear la base de datos fácilmente."

---

## Diapositiva 8: Estructura del Proyecto (Los 3 niveles)
- **Texto**: "Para que se entienda fácil, mi proyecto se divide en 3 capas que trabajan juntas: 
    1. El **Almacén** (la base de datos MySQL).
    2. El **Motor** (el servidor PHP que procesa todo).
    3. La **Interfaz** (lo que el cliente ve en su navegador)."

---

## Diapositiva 9: Docker: Todo en contenedores
- **Texto**: "Gracias a Docker, no tengo que instalar nada raro en el ordenador del cliente. Todo va en contenedores separados que se levantan con un solo comando, lo que hace que el despliegue sea súper rápido."

---

## Diapositiva 10: Panel de Administración
- **Texto**: "He creado un panel especial para el dueño del negocio. Desde ahí puede ver todas las citas del día y, con un solo clic, confirmarlas o cancelarlas. Es una gestión muy limpia y directa."

---

## Diapositiva 11: Pruebas de funcionamiento
- **Texto**: "He probado el sistema a fondo. Por ejemplo, el sistema es capaz de detectar si alguien intenta reservar a una hora que ya está ocupada y le avisa para que elija otra. Todo el flujo desde que te registras hasta que tienes tu cita confirmada funciona sin errores."

---

## Diapositiva 12: Retos Técnicos
- **Texto**: "El mayor reto ha sido conseguir que los contenedores de Docker se comunicaran bien entre ellos (que la web viera la base de datos). También tuve que pelearme con el Proxy para conseguir que el candado de seguridad (HTTPS) funcionara correctamente en el dominio."

---

## Diapositiva 13: Futuro y Conclusión
- **Mejoras pensadas**: 
    - "Esto es solo el principio. Me gustaría añadir la típica pregunta de **'¿Se te ha olvidado la contraseña?'** para que el cliente pueda recuperarla él mismo."
    - "También quiero que lleguen **notificaciones por SMS, correo o WhatsApp** cuando el administrador confirme o cancele una cita."
- **Conclusión**: "Estoy muy contento con el resultado. He pasado de tener solo código a tener una aplicación real, segura y lista para ser usada en un negocio de verdad."
