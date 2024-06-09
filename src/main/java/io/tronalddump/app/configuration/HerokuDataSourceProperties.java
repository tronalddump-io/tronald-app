package io.tronalddump.app.configuration;

import org.springframework.beans.factory.annotation.Value;
import org.springframework.boot.autoconfigure.condition.ConditionalOnProperty;
import org.springframework.context.annotation.Configuration;

import java.net.URI;

@ConditionalOnProperty(prefix = "spring.datasource", name = "uri")
@Configuration
public class HerokuDataSourceProperties implements DataSourceProperties {

  private static final String SCHEMA_POSTGRESQL = "postgresql";

  private final String driverClassName;
  private final String password;
  private final String url;
  private final String username;

  public HerokuDataSourceProperties(
      @Value("${spring.datasource.driver-class-name}") String driverClassName,
      @Value("${spring.datasource.uri}") String uniformResourceIdentifier
  ) {
    URI uri = URI.create(uniformResourceIdentifier);

    String[] userInfo = uri.getUserInfo().split(":");

    this.driverClassName = driverClassName;
    this.password = userInfo[1];
    this.url = String.format(
        "jdbc:%s://%s:%s/%s",
        this.scheme(uri),
        uri.getHost().replace("/", ""),
        uri.getPort(),
        uri.getPath().replace("/", "")
    );
    this.username = userInfo[0];
  }

  private String scheme(URI uri) {
    if ("postgres".compareToIgnoreCase(uri.getScheme()) == 0) {
      return SCHEMA_POSTGRESQL;
    }

    throw new RuntimeException(
        String.format("Unsupported schema %s given", uri.getScheme())
    );
  }

  @Override
  public String getDriverClassName() {
    return this.driverClassName;
  }

  @Override
  public String getPassword() {
    return this.password;
  }

  @Override
  public String getUrl() {
    return this.url;
  }

  @Override
  public String getUsername() {
    return this.username;
  }
}
