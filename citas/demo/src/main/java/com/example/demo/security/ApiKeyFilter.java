package com.example.demo.security;

import jakarta.servlet.FilterChain;
import jakarta.servlet.ServletException;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpServletResponse;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.security.authentication.UsernamePasswordAuthenticationToken;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.authority.AuthorityUtils;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.stereotype.Component;
import org.springframework.web.filter.OncePerRequestFilter;

import java.io.IOException;

@Component
public class ApiKeyFilter extends OncePerRequestFilter {

    // Inyectamos el valor desde application.properties
    @Value("${app.api-key}")
    private String expectedApiKey; // Le cambié el nombre para que sea más claro

    @Override
    protected void doFilterInternal(HttpServletRequest request,
                                    HttpServletResponse response,
                                    FilterChain filterChain)
            throws ServletException, IOException {

        // 1. Permitir peticiones OPTIONS (CORS preflight) sin validar
        if (request.getMethod().equalsIgnoreCase("OPTIONS")) {
            filterChain.doFilter(request, response);
            return;
        }

        // 2. Obtener la API Key de la petición
        String requestApiKey = request.getHeader("X-API-Key");

        System.out.println(">>> API KEY enviada: " + requestApiKey);
        // ¡OJO! En producción no deberías imprimir la clave esperada por seguridad,
        // pero para depurar está bien.
        System.out.println(">>> API KEY esperada: " + expectedApiKey);

        // 3. Validar la API Key
        // Verificamos si es nula O si no coincide con la esperada
        if (requestApiKey == null || !requestApiKey.equals(expectedApiKey)) {
            // Si falla, devolvemos error 401 y cortamos el flujo
            System.out.println(">>> API KEY INVÁLIDA - Acceso denegado");
            response.setStatus(HttpServletResponse.SC_UNAUTHORIZED);
            response.getWriter().write("Invalid API Key");
            return; // ¡Importante! No seguimos ejecutando
        }

        // 4. Si llegamos aquí, la clave es válida
        System.out.println(">>> API KEY correcta, dejando pasar la petición...");
        
        // Autenticamos al usuario en el contexto de Spring Security
        Authentication authentication = new UsernamePasswordAuthenticationToken(requestApiKey, null, AuthorityUtils.NO_AUTHORITIES);
        SecurityContextHolder.getContext().setAuthentication(authentication);

        // Dejamos continuar la petición hacia el controlador
        filterChain.doFilter(request, response);
    }

    @Override
    protected boolean shouldNotFilter(HttpServletRequest request) {
        // Rutas públicas que NO requieren API KEY (ej: Swagger, health checks)
        String path = request.getServletPath();
        // Puedes añadir más rutas aquí si lo necesitas
        return path.startsWith("/public");
    }
}