// ** I18N

// Calendar ES (español - spanish) language
// Author: Pablo Bngs, <pablo@bng5.net>
// Encoding: UTF-8
// Distribuido bajo los mismos términos que el calendario en sí mismo.

// días
Calendar._DN = new Array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
Calendar._SDN = new Array("Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom");
// Primer día, a mostrar, de la semana.
// Entero, correspondiente a las claves del array "Calendar._DN".
Calendar._FD = 0;

// meses
Calendar._MN = new Array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Setiembre", "Octubre", "Noviembre", "Diciembre");
Calendar._SMN = new Array("Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Set", "Oct", "Nov", "Dic");
// tooltips
Calendar._TT = {};
Calendar._TT["INFO"] = "Acerca del calendario";

Calendar._TT["ABOUT"] =
"Selector DHTML de Fecha/Hora\n" +
"(c) dynarch.com 2002-2005 / Author: Mihai Bazon\n" + // don't translate this this ;-)
"Para conseguir la ?ima versi?visite: http://www.dynarch.com/projects/calendar/\n" +
"Distribuido bajo licencia GNU LGPL. Visite http://gnu.org/licenses/lgpl.html para m?detalles." +
"\n\n" +
"Selecci?de fecha:\n" +
"- Use los botones \xab, \xbb para seleccionar el a?n" +
"- Use los botones " + String.fromCharCode(0x2039) + ", " + String.fromCharCode(0x203a) + " para seleccionar el mes\n" +
"- Mantenga pulsado el rat?en cualquiera de estos botones para una selecci?r?da.";
Calendar._TT["ABOUT_TIME"] = "\n\n" +
"Selecci?de hora:\n" +
"- Pulse en cualquiera de las partes de la hora para incrementarla\n" +
"- o pulse las may?ulas mientras hace clic para decrementarla\n" +
"- o haga clic y arrastre el rat?para una selecci?m?r?da.";

Calendar._TT["PREV_YEAR"] = "Año anterior";// (mantener para menú)
Calendar._TT["PREV_MONTH"] = "Mes anterior";// (mantener para menú)
Calendar._TT["GO_TODAY"] = "Ir a hoy";
Calendar._TT["NEXT_MONTH"] = "Mes siguiente";// (mantener para menú)
Calendar._TT["NEXT_YEAR"] = "Año siguiente";// (mantener para menú)
Calendar._TT["SEL_DATE"] = "Seleccionar fecha";
Calendar._TT["DRAG_TO_MOVE"] = "Arrastrar para mover";
Calendar._TT["PART_TODAY"] = " (hoy)";

// the following is to inform that "%s" is to be the first day of week
// %s will be replaced with the day name.
Calendar._TT["DAY_FIRST"] = "Fijar primer día de la semana";//Hacer %s primer día de la semana";

// This may be locale-dependent.  It specifies the week-end days, as an array
// of comma-separated numbers.  The numbers are from 0 to 6: 0 means Sunday, 1
// means Monday, etc.
Calendar._TT["WEEKEND"] = "0,6";

Calendar._TT["CLOSE"] = "Cerrar";
Calendar._TT["TODAY"] = "Hoy";
Calendar._TT["TIME_PART"] = "(Mayúscula-)Clic o arrastre para cambiar valor";

// date formats
Calendar._TT["DEF_DATE_FORMAT"] = "%d/%m/%Y";
Calendar._TT["TT_DATE_FORMAT"] = "%l, %d de %F de %Y";

Calendar._TT["WK"] = "sem";
Calendar._TT["TIME"] = "Hora:";
