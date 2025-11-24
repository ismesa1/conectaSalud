package com.example.demo.config; // Ojo con el paquete, si lo tienes en .config o .security

import com.example.demo.security.ApiKeyFilter;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;
import org.springframework.http.HttpMethod; // <--- ¡ESTA IMPORTACIÓN ES CLAVE!
import org.springframework.security.config.annotation.web.builders.HttpSecurity;
import org.springframework.security.web.SecurityFilterChain;
import org.springframework.security.web.authentication.UsernamePasswordAuthenticationFilter;

@Configuration
public class SecurityConfig {

    @Autowired
    private ApiKeyFilter apiKeyFilter;

    @Bean
    public SecurityFilterChain filterChain(HttpSecurity http) throws Exception {

        http
            // Desactivamos CSRF porque usamos API Keys, no sesiones de navegador
            .csrf(csrf -> csrf.disable())
            
            // Configuramos las reglas de autorización
            .authorizeHttpRequests(auth -> auth
                // Permitir preflight requests de CORS (opcional pero recomendado)
                .requestMatchers(HttpMethod.OPTIONS, "/**").permitAll()
                
                // Rutas públicas que no requieren autenticación
                // Asegúrate de que coincidan con lo que pusiste en shouldNotFilter de ApiKeyFilter
                .requestMatchers("/auth/**", "/public/**").permitAll()
                
                // Cualquier otra petición requiere estar autenticada
                .anyRequest().authenticated()
            )
            
            // Añadimos nuestro filtro de API Key antes del filtro de autenticación estándar
            .addFilterBefore(apiKeyFilter, UsernamePasswordAuthenticationFilter.class);

        return http.build();
    }
}