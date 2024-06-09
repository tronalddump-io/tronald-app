package io.tronalddump.app.configuration;

import org.springframework.boot.context.properties.ConfigurationProperties;

@ConfigurationProperties(prefix = "spring.datasource")
public interface DataSourceProperties {

  String getDriverClassName();

  String getPassword();

  String getUrl();

  String getUsername();
}