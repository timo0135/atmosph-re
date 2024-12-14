<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="UTF-8" indent="yes"/>

    <xsl:template match="/">

                <header>
                    <h2>Prévisions Météo</h2>
                </header>
                <div class="container">
                    <div class="period">
                        <h3>Nuit</h3>
                        <xsl:apply-templates select="//echeance[@hour = 3]"/>
                    </div>
                    <div class="period">
                        <h3>Matin</h3>
                        <xsl:apply-templates select="//echeance[@hour = 9 ]"/>
                    </div>
                    <div class="period">
                        <h3>Midi</h3>
                        <xsl:apply-templates select="//echeance[@hour = 12]"/>
                    </div>
                    <div class="period">
                        <h3>Après-midi</h3>
                        <xsl:apply-templates select="//echeance[@hour = 15]"/>
                    </div>
                    <div class="period">
                        <h3>Soir</h3>
                        <xsl:apply-templates select="//echeance[@hour = 18]"/>
                    </div>
                </div>
    </xsl:template>

    <xsl:template match="echeance">
        <div class="forecast">
            <p class="temperature">
                <span class="warm">🌡️<xsl:value-of select="format-number(temperature/level[@val='2m'] - 273.15, '0.00')"/> °C</span>
            </p>
            <p class="rain">
                <xsl:choose>
                    <xsl:when test="risque_neige = 'oui'">
                        <span>🌨️</span>
                    </xsl:when>
                    <xsl:when test="pluie &gt; 0">
                        <span>🌧️</span>
                    </xsl:when>
                    <xsl:when test="pluie = 0 and format-number(temperature/level[@val='2m'] - 273.15, '0.00') &gt; 15">
                        <span>☀️</span>
                    </xsl:when>
                    <xsl:otherwise>
                        <span>☁️</span>
                    </xsl:otherwise>
                </xsl:choose>
            </p>
            <p class="wind">
                <xsl:choose>
                    <xsl:when test="vent_moyen/level[@val='10m'] &gt; 10">
                        <span>💨 <p><xsl:value-of select="vent_moyen/level[@val='10m']"/> km/h</p></span>
                    </xsl:when>
                </xsl:choose>
            </p>
        </div>
    </xsl:template>
</xsl:stylesheet>
