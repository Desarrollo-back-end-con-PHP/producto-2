<?php

namespace App\Helpers;

class ProfileMessageHelper
{

    const EXITO_DATOS = 'exito_datos';
    const EXITO_PASS = 'exito_pass';
    const EXITO_RESERVA = 'exito_reserva';
    const ERROR_DATOS = 'error_datos';
    const ERROR_BD_PASS = 'error_bd_pass';
    const ERROR_PASS_MISMATCH = 'error_pass_mismatch';
    const ERROR_DESCONOCIDO = 'error_desconocido';

    const ERROR_PASS_SHORT = 'error_pass_short';

    const ERROR_EMAIL = 'error_email';

    const ERROR_CAMPOS_VACIOS = 'error_campos_vacios';

    public static function getText(?string $mensaje): string
    {
        return match ($mensaje) {
            self::EXITO_DATOS => '¡Datos personales actualizados con éxito!',
            self::EXITO_PASS => '¡Contraseña actualizada con éxito!',
            self::EXITO_RESERVA => 'Tu reserva se ha procesado correctamente.',
            self::ERROR_DATOS => 'Error al actualizar los datos personales. Inténtalo de nuevo.',
            self::ERROR_BD_PASS => 'Error al guardar la nueva contraseña en la base de datos.',
            self::ERROR_PASS_MISMATCH => 'Las contraseñas no coinciden o están vacías. Inténtalo de nuevo.',
            self::ERROR_PASS_SHORT => 'Tu contraseña es muy corta. Por favor, usa al menos 8 caracteres.',
            self::ERROR_EMAIL => 'Por favor, introduce un correo electrónico válido.',
            self::ERROR_CAMPOS_VACIOS => 'Todos los campos son obligatorios. Asegúrate de completar todos antes de guardar.',
            'PROFILE_REQUIRED' => 'Tu perfil está incompleto. Por favor, rellena tus datos para continuar con la reserva.',
            default => '',
        };
    }

    public static function getClaseAlerta(?string $mensaje): string
    {
        return match ($mensaje) {
            self::EXITO_DATOS,
            self::EXITO_PASS,
            self::EXITO_RESERVA => 'alert-success',

            'PROFILE_REQUIRED' => 'alert-warning',

            self::ERROR_DATOS,
            self::ERROR_BD_PASS,
            self::ERROR_CAMPOS_VACIOS,
            self::ERROR_EMAIL,
            self::ERROR_PASS_SHORT,
            self::ERROR_PASS_MISMATCH => 'alert-danger',

            default => '',
        };
    }
}
