module.exports = {
  preset         : 'ts-jest/presets/default-esm',   // Versão ESM do ts-jest
  testEnvironment: 'node',
  transform      : {
    '^.+\\.tsx?$': ['ts-jest', { useESM: true }],
  },
  extensionsToTreatAsEsm: ['.ts'],
};