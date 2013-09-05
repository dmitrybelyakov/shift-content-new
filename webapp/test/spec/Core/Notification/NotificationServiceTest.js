'use strict';

describe('NotificationService: ', function () {

  var timeout;
  var service;

  //init app
  beforeEach(module('shiftContentApp'));

  beforeEach(inject(function($timeout, NotificationService){
    timeout = $timeout;
    service = NotificationService;
  }));

  //-------------------------------------------------------------------------

  /*
   * Notifications
   */
  describe('Manage notifications', function(){

    it('normalizes queue name', function () {
      expect(service.queueName('some name')).toBe('somename');
    });

    it('can correctly prosess timeout strings', function () {
      expect(service.parseTimeout()).toBe(0);
      expect(service.parseTimeout('1')).toBe(1);
      expect(service.parseTimeout('1ms')).toBe(1);
      expect(service.parseTimeout('1s')).toBe(1000);
    });

    it('can get queue by name', function () {
      var queue = ['me is test queue'];
      service.queues.test = queue;
      expect(service.getQueue('test')).toEqual(queue);
    });

    it('creates new queue when getting by name', function () {
      expect(service.queues).toEqual([]);
      expect(service.getQueue('test')).toEqual([]);
      expect(service.queues.test).toBeDefined();
    });

    it('can send message to queue', function () {
      var message = 'Test message'
      service.send('test', 'error', message, 1);
      var queue = service.getQueue('test');
      expect(queue[0].message).toEqual(message);
    });

    it('removes message from queue after timeout', function () {
      var message = 'Test message'
      service.send('test', 'error', message, 1);
      var queue = service.getQueue('test');
      expect(queue[0].message).toEqual(message);

      //assert removed
      timeout(function(){
        expect(service.getQueue('test')).toEqual([]);
      }, 1)
    });

    it('allows to remove message from queue by id', function () {
      var message = 'Test message'
      service.send('test', 'error', message, 1);
      var queue = service.getQueue('test');
      service.removeNotification(queue[0].id);
      expect(service.getQueue('test')).toEqual([]);
    });

  }); //notifications



  /*
   * Growls
   */
  describe('Manage growls', function(){

    it('can growl', function () {
      service.growl('Grrr', 0);
      expect(service.growls[0].message).toBe('Grrr');
    });

    it('can get all growls', function () {
      service.growl('1', 0);
      service.growl('2', 0);
      var growls = service.getGrowls();
      expect(growls[0].message).toBe('1');
      expect(growls[1].message).toBe('2');
    });

    it('removes growl after timeout', function () {
      service.growl('Grrr', 1);
      expect(service.getGrowls().length).toBe(1);
      timeout(function(){
        expect(service.getGrowls().length).toBe(0);
      }, 1)
    });

    it('can remove growl by id', function () {
      service.growl('Grrr', 1);
      expect(service.getGrowls().length).toBe(1);
      service.removeGrowl(service.getGrowls()[0].id);
      expect(service.getGrowls().length).toBe(0);
    });

  }); //growls



});
