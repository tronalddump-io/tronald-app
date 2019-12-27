FROM matchilling/imagemagick7-centos

MAINTAINER Mathias Schilling <m@matchilling.com>

ENV JAVA_VERSION="1.8.0-openjdk"

RUN yum -y update && \
    yum -y install "java-${JAVA_VERSION}" && \
    yum clean all

ARG JAR_FILE
COPY ${JAR_FILE} app.jar

# This is a bit of a hack but it ensures that the MemeGenerator can pass file
# references to the ImageMagick tools without having to stream everything back
# and forth as we are running in a JAR.
RUN mkdir -p "src/main/resources/meme/font/"
COPY "meme/font/" "src/main/resources/meme/font/"

RUN mkdir -p "src/main/resources/meme/image/"
COPY "meme/image/" "src/main/resources/meme/image/"

CMD ["sh","-c","java $JAVA_OPTS -Dserver.port=$PORT -Xmx300m -Xss512k -XX:CICompilerCount=2 -Dfile.encoding=UTF-8 -XX:+UseContainerSupport -Djava.security.egd=file:/dev/./urandom -jar /app.jar"]