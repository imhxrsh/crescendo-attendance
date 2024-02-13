import { StatusBar } from 'expo-status-bar';
import { Button, StyleSheet, Text, View, Linking } from 'react-native';
import React, { useState, useEffect } from 'react';
import { Camera } from 'expo-camera';
import * as Font from 'expo-font';

export default function App() {
  const [hasPermission, setHasPermission] = useState(null);
  const [scanData, setScanData] = useState(null);
  const [screen, setScreen] = useState('options');
  const [apiLink, setApiLink] = useState(null);
  const [selectedOption, setSelectedOption] = useState('');
  const [fontLoaded, setFontLoaded] = useState(false);

  useEffect(() => {
    async function loadFont() {
      await Font.loadAsync({
        'montserrat-regular': require('./assets/fonts/Montserrat-Regular.ttf'),
        'montserrat-bold': require('./assets/fonts/Montserrat-Bold.ttf'),
      });
      setFontLoaded(true);
    }

    loadFont();
  }, []);

  useEffect(() => {
    (async () => {
      const { status } = await Camera.requestCameraPermissionsAsync();
      setHasPermission(status === 'granted');
    })();
  }, []);

  const handleOptionSelected = (link, optionName) => {
    setApiLink(link);
    setSelectedOption(optionName);
    setScreen('scanner');
  };

  const handleBarCodeScanned = async ({ type, data }) => {
    setScanData(data);
    setScreen('result');
    console.log(`Bar code with type ${type} and data ${data} has been scanned!`);
    try {
      const response = await fetch(apiLink, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ scannedData: data }),
      });
      const responseData = await response.json();
      console.log('POST Response:', responseData);
    } catch (error) {
      console.error('Error sending POST request:', error);
    }
  };

  const resetScan = () => {
    setScanData(null);
    setScreen('options');
    setSelectedOption('');
  };

  if (!fontLoaded || !hasPermission) {
    return null;
  }

  return (
    <View style={[styles.container, styles.darkBackground]}>
      {screen === 'options' && (
        <View>
          <Text style={[styles.title, styles.fontMontserrat]}>Crescendo Attendance</Text>
          <View style={styles.options}>
            <Button title="Mech-a-thon" onPress={() => handleOptionSelected('http://localhost:3000/api/mechathon.php', 'Mech-a-thon')} style={styles.button} />
            <View style={styles.buttonMargin} />
            <Button title="Elex-a-thon" onPress={() => handleOptionSelected('http://localhost:3000/api/elexathon.php', 'Elex-a-thon')} style={styles.button} />
            <View style={styles.buttonMargin} />
            <Button title="Food" onPress={() => handleOptionSelected('http://localhost:3000/api/food.php', 'Food')} style={styles.button} />
          </View>
        </View>
      )}
      {screen === 'scanner' && (
        <View style={styles.scanner}>
          <Text style={[styles.whiteText, styles.padding, styles.fontMontserrat]}>Scanning for: {selectedOption}</Text>
          <Camera
            style={styles.cameraPreview}
            type={Camera.Constants.Type.back}
            onBarCodeScanned={handleBarCodeScanned}
          />
          <View style={styles.cancelButton}>
            <Button title="Cancel" onPress={resetScan} color="#007AFF" />
          </View>
        </View>
      )}
      {screen === 'result' && (
        <View style={styles.result}>
          <Text style={[styles.whiteText, styles.fontMontserrat]}>Scanned data: {scanData}</Text>
          <View style={styles.buttonMargin} />
          <Button title="Scan Again" onPress={resetScan} color="#2196f3" />
        </View>
      )}
      {screen === 'options' && (
        <View style={styles.footer}>
          <Text style={[styles.footerText, styles.fontMontserrat]}>Developed and Designed by <Text style={[styles.link, styles.fontMontserrat]} onPress={() => Linking.openURL('https://github.com/harshvishwakarma404')}>Harsh Vishwakarma</Text></Text>
        </View>
      )}
      <StatusBar style="light" />
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
  },
  darkBackground: {
    backgroundColor: '#333333',
  },
  whiteText: {
    color: '#FFFFFF',
  },
  options: {
    flexDirection: 'column',
    alignItems: 'center',
    justifyContent: 'center',
    marginTop: 20,
  },
  buttonMargin: {
    marginVertical: 10,
  },
  button: {
    marginVertical: 10,
  },
  scanner: {
    flex: 1,
    width: '100%',
    alignItems: 'center',
    justifyContent: 'center',
  },
  cameraPreview: {
    flex: 1,
    width: '155%',
  },
  cancelButton: {
    position: 'absolute',
    bottom: 20,
  },
  result: {
    flex: 1,
    alignItems: 'center',
    justifyContent: 'center',
  },
  padding: {
    padding: 20,
  },
  title: {
    fontSize: 40,
    fontWeight: 'bold',
    color: '#FFFFFF',
    marginBottom: 50,
  },
  footer: {
    position: 'absolute',
    bottom: 10,
    width: '100%',
    alignItems: 'center',
    justifyContent: 'center',
  },
  footerText: {
    fontSize: 20,
    textAlign: 'center',
    color: '#FFFFFF',
  },
  link: {
    color: '#007AFF',
  },
  fontMontserrat: {
    fontFamily: 'montserrat-regular',
  },
});
