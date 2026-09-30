let c = require('../utils/mixinLeftComponent.ts');
let ManageData = require('../utils/ManageData.ts');

function emit(event, data) {
    {event}(data)
}

describe('test request', () => {   
  test('should make a request when the filter changes', () => {
    console.log("Pasta do arquivo:", __dirname);
    c.setEmitFunction(emit);
    c.makeRequestWhenChange([{title: 'test', name: 'test'}]);

    expect(increment(0, 10)).toBe(1)
  })
})