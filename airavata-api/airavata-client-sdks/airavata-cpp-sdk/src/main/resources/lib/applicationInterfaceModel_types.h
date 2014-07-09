/**
 * Autogenerated by Thrift Compiler (0.9.1)
 *
 * DO NOT EDIT UNLESS YOU ARE SURE THAT YOU KNOW WHAT YOU ARE DOING
 *  @generated
 */
#ifndef applicationInterfaceModel_TYPES_H
#define applicationInterfaceModel_TYPES_H

#include <thrift/Thrift.h>
#include <thrift/TApplicationException.h>
#include <thrift/protocol/TProtocol.h>
#include <thrift/transport/TTransport.h>

#include <thrift/cxxfunctional.h>




struct DataType {
  enum type {
    STRING = 0,
    INTEGER = 1,
    FLOAT = 2,
    URI = 3
  };
};

extern const std::map<int, const char*> _DataType_VALUES_TO_NAMES;

typedef struct _InputDataObjectType__isset {
  _InputDataObjectType__isset() : value(false), type(false), applicationArgument(false), standardInput(true), userFriendlyDescription(false), metaData(false) {}
  bool value;
  bool type;
  bool applicationArgument;
  bool standardInput;
  bool userFriendlyDescription;
  bool metaData;
} _InputDataObjectType__isset;

class InputDataObjectType {
 public:

  static const char* ascii_fingerprint; // = "24F962C1CE4BE9FBD0F5D5EE9D1D5C00";
  static const uint8_t binary_fingerprint[16]; // = {0x24,0xF9,0x62,0xC1,0xCE,0x4B,0xE9,0xFB,0xD0,0xF5,0xD5,0xEE,0x9D,0x1D,0x5C,0x00};

  InputDataObjectType() : name(), value(), type((DataType::type)0), applicationArgument(), standardInput(false), userFriendlyDescription(), metaData() {
  }

  virtual ~InputDataObjectType() throw() {}

  std::string name;
  std::string value;
  DataType::type type;
  std::string applicationArgument;
  bool standardInput;
  std::string userFriendlyDescription;
  std::string metaData;

  _InputDataObjectType__isset __isset;

  void __set_name(const std::string& val) {
    name = val;
  }

  void __set_value(const std::string& val) {
    value = val;
    __isset.value = true;
  }

  void __set_type(const DataType::type val) {
    type = val;
    __isset.type = true;
  }

  void __set_applicationArgument(const std::string& val) {
    applicationArgument = val;
    __isset.applicationArgument = true;
  }

  void __set_standardInput(const bool val) {
    standardInput = val;
    __isset.standardInput = true;
  }

  void __set_userFriendlyDescription(const std::string& val) {
    userFriendlyDescription = val;
    __isset.userFriendlyDescription = true;
  }

  void __set_metaData(const std::string& val) {
    metaData = val;
    __isset.metaData = true;
  }

  bool operator == (const InputDataObjectType & rhs) const
  {
    if (!(name == rhs.name))
      return false;
    if (__isset.value != rhs.__isset.value)
      return false;
    else if (__isset.value && !(value == rhs.value))
      return false;
    if (__isset.type != rhs.__isset.type)
      return false;
    else if (__isset.type && !(type == rhs.type))
      return false;
    if (__isset.applicationArgument != rhs.__isset.applicationArgument)
      return false;
    else if (__isset.applicationArgument && !(applicationArgument == rhs.applicationArgument))
      return false;
    if (__isset.standardInput != rhs.__isset.standardInput)
      return false;
    else if (__isset.standardInput && !(standardInput == rhs.standardInput))
      return false;
    if (__isset.userFriendlyDescription != rhs.__isset.userFriendlyDescription)
      return false;
    else if (__isset.userFriendlyDescription && !(userFriendlyDescription == rhs.userFriendlyDescription))
      return false;
    if (__isset.metaData != rhs.__isset.metaData)
      return false;
    else if (__isset.metaData && !(metaData == rhs.metaData))
      return false;
    return true;
  }
  bool operator != (const InputDataObjectType &rhs) const {
    return !(*this == rhs);
  }

  bool operator < (const InputDataObjectType & ) const;

  uint32_t read(::apache::thrift::protocol::TProtocol* iprot);
  uint32_t write(::apache::thrift::protocol::TProtocol* oprot) const;

};

void swap(InputDataObjectType &a, InputDataObjectType &b);

typedef struct _OutputDataObjectType__isset {
  _OutputDataObjectType__isset() : value(false), type(false) {}
  bool value;
  bool type;
} _OutputDataObjectType__isset;

class OutputDataObjectType {
 public:

  static const char* ascii_fingerprint; // = "B33AE596EF78C48424CF96BCA5D1DF99";
  static const uint8_t binary_fingerprint[16]; // = {0xB3,0x3A,0xE5,0x96,0xEF,0x78,0xC4,0x84,0x24,0xCF,0x96,0xBC,0xA5,0xD1,0xDF,0x99};

  OutputDataObjectType() : name(), value(), type((DataType::type)0) {
  }

  virtual ~OutputDataObjectType() throw() {}

  std::string name;
  std::string value;
  DataType::type type;

  _OutputDataObjectType__isset __isset;

  void __set_name(const std::string& val) {
    name = val;
  }

  void __set_value(const std::string& val) {
    value = val;
    __isset.value = true;
  }

  void __set_type(const DataType::type val) {
    type = val;
    __isset.type = true;
  }

  bool operator == (const OutputDataObjectType & rhs) const
  {
    if (!(name == rhs.name))
      return false;
    if (__isset.value != rhs.__isset.value)
      return false;
    else if (__isset.value && !(value == rhs.value))
      return false;
    if (__isset.type != rhs.__isset.type)
      return false;
    else if (__isset.type && !(type == rhs.type))
      return false;
    return true;
  }
  bool operator != (const OutputDataObjectType &rhs) const {
    return !(*this == rhs);
  }

  bool operator < (const OutputDataObjectType & ) const;

  uint32_t read(::apache::thrift::protocol::TProtocol* iprot);
  uint32_t write(::apache::thrift::protocol::TProtocol* oprot) const;

};

void swap(OutputDataObjectType &a, OutputDataObjectType &b);

typedef struct _ApplicationInterfaceDescription__isset {
  _ApplicationInterfaceDescription__isset() : applicationDesription(false), applicationModules(false), applicationInputs(false), applicationOutputs(false) {}
  bool applicationDesription;
  bool applicationModules;
  bool applicationInputs;
  bool applicationOutputs;
} _ApplicationInterfaceDescription__isset;

class ApplicationInterfaceDescription {
 public:

  static const char* ascii_fingerprint; // = "355A0972969341C2A113049339427849";
  static const uint8_t binary_fingerprint[16]; // = {0x35,0x5A,0x09,0x72,0x96,0x93,0x41,0xC2,0xA1,0x13,0x04,0x93,0x39,0x42,0x78,0x49};

  ApplicationInterfaceDescription() : applicationInterfaceId("DO_NOT_SET_AT_CLIENTS"), applicationName(), applicationDesription() {
  }

  virtual ~ApplicationInterfaceDescription() throw() {}

  std::string applicationInterfaceId;
  std::string applicationName;
  std::string applicationDesription;
  std::vector<std::string>  applicationModules;
  std::vector<InputDataObjectType>  applicationInputs;
  std::vector<OutputDataObjectType>  applicationOutputs;

  _ApplicationInterfaceDescription__isset __isset;

  void __set_applicationInterfaceId(const std::string& val) {
    applicationInterfaceId = val;
  }

  void __set_applicationName(const std::string& val) {
    applicationName = val;
  }

  void __set_applicationDesription(const std::string& val) {
    applicationDesription = val;
    __isset.applicationDesription = true;
  }

  void __set_applicationModules(const std::vector<std::string> & val) {
    applicationModules = val;
    __isset.applicationModules = true;
  }

  void __set_applicationInputs(const std::vector<InputDataObjectType> & val) {
    applicationInputs = val;
    __isset.applicationInputs = true;
  }

  void __set_applicationOutputs(const std::vector<OutputDataObjectType> & val) {
    applicationOutputs = val;
    __isset.applicationOutputs = true;
  }

  bool operator == (const ApplicationInterfaceDescription & rhs) const
  {
    if (!(applicationInterfaceId == rhs.applicationInterfaceId))
      return false;
    if (!(applicationName == rhs.applicationName))
      return false;
    if (__isset.applicationDesription != rhs.__isset.applicationDesription)
      return false;
    else if (__isset.applicationDesription && !(applicationDesription == rhs.applicationDesription))
      return false;
    if (__isset.applicationModules != rhs.__isset.applicationModules)
      return false;
    else if (__isset.applicationModules && !(applicationModules == rhs.applicationModules))
      return false;
    if (__isset.applicationInputs != rhs.__isset.applicationInputs)
      return false;
    else if (__isset.applicationInputs && !(applicationInputs == rhs.applicationInputs))
      return false;
    if (__isset.applicationOutputs != rhs.__isset.applicationOutputs)
      return false;
    else if (__isset.applicationOutputs && !(applicationOutputs == rhs.applicationOutputs))
      return false;
    return true;
  }
  bool operator != (const ApplicationInterfaceDescription &rhs) const {
    return !(*this == rhs);
  }

  bool operator < (const ApplicationInterfaceDescription & ) const;

  uint32_t read(::apache::thrift::protocol::TProtocol* iprot);
  uint32_t write(::apache::thrift::protocol::TProtocol* oprot) const;

};

void swap(ApplicationInterfaceDescription &a, ApplicationInterfaceDescription &b);



#endif
