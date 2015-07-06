/**
 * Licensed to the Apache Software Foundation (ASF) under one or more
 * contributor license agreements.  See the NOTICE file distributed with
 * this work for additional information regarding copyright ownership.
 * The ASF licenses this file to You under the Apache License, Version 2.0
 * (the "License"); you may not use this file except in compliance with
 * the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

/**
 * Autogenerated by Thrift Compiler (0.9.2)
 *
 * DO NOT EDIT UNLESS YOU ARE SURE THAT YOU KNOW WHAT YOU ARE DOING
 *  @generated
 */
package org.apache.airavata.model.job;

import org.apache.thrift.scheme.IScheme;
import org.apache.thrift.scheme.SchemeFactory;
import org.apache.thrift.scheme.StandardScheme;

import org.apache.thrift.scheme.TupleScheme;
import org.apache.thrift.protocol.TTupleProtocol;
import org.apache.thrift.protocol.TProtocolException;
import org.apache.thrift.EncodingUtils;
import org.apache.thrift.TException;
import org.apache.thrift.async.AsyncMethodCallback;
import org.apache.thrift.server.AbstractNonblockingServer.*;
import java.util.List;
import java.util.ArrayList;
import java.util.Map;
import java.util.HashMap;
import java.util.EnumMap;
import java.util.Set;
import java.util.HashSet;
import java.util.EnumSet;
import java.util.Collections;
import java.util.BitSet;
import java.nio.ByteBuffer;
import java.util.Arrays;
import javax.annotation.Generated;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

@SuppressWarnings({"cast", "rawtypes", "serial", "unchecked"})
@Generated(value = "Autogenerated by Thrift Compiler (0.9.2)", date = "2015-7-6")
public class JobModel implements org.apache.thrift.TBase<JobModel, JobModel._Fields>, java.io.Serializable, Cloneable, Comparable<JobModel> {
  private static final org.apache.thrift.protocol.TStruct STRUCT_DESC = new org.apache.thrift.protocol.TStruct("JobModel");

  private static final org.apache.thrift.protocol.TField JOB_ID_FIELD_DESC = new org.apache.thrift.protocol.TField("jobId", org.apache.thrift.protocol.TType.STRING, (short)1);
  private static final org.apache.thrift.protocol.TField TASK_ID_FIELD_DESC = new org.apache.thrift.protocol.TField("taskId", org.apache.thrift.protocol.TType.STRING, (short)2);
  private static final org.apache.thrift.protocol.TField JOB_DESCRIPTION_FIELD_DESC = new org.apache.thrift.protocol.TField("jobDescription", org.apache.thrift.protocol.TType.STRING, (short)3);
  private static final org.apache.thrift.protocol.TField CREATION_TIME_FIELD_DESC = new org.apache.thrift.protocol.TField("creationTime", org.apache.thrift.protocol.TType.I64, (short)4);
  private static final org.apache.thrift.protocol.TField JOB_STATUS_FIELD_DESC = new org.apache.thrift.protocol.TField("jobStatus", org.apache.thrift.protocol.TType.STRUCT, (short)5);
  private static final org.apache.thrift.protocol.TField COMPUTE_RESOURCE_CONSUMED_FIELD_DESC = new org.apache.thrift.protocol.TField("computeResourceConsumed", org.apache.thrift.protocol.TType.STRING, (short)6);
  private static final org.apache.thrift.protocol.TField JOB_NAME_FIELD_DESC = new org.apache.thrift.protocol.TField("jobName", org.apache.thrift.protocol.TType.STRING, (short)7);
  private static final org.apache.thrift.protocol.TField WORKING_DIR_FIELD_DESC = new org.apache.thrift.protocol.TField("workingDir", org.apache.thrift.protocol.TType.STRING, (short)8);

  private static final Map<Class<? extends IScheme>, SchemeFactory> schemes = new HashMap<Class<? extends IScheme>, SchemeFactory>();
  static {
    schemes.put(StandardScheme.class, new JobModelStandardSchemeFactory());
    schemes.put(TupleScheme.class, new JobModelTupleSchemeFactory());
  }

  private String jobId; // required
  private String taskId; // required
  private String jobDescription; // required
  private long creationTime; // optional
  private org.apache.airavata.model.status.JobStatus jobStatus; // optional
  private String computeResourceConsumed; // optional
  private String jobName; // optional
  private String workingDir; // optional

  /** The set of fields this struct contains, along with convenience methods for finding and manipulating them. */
  public enum _Fields implements org.apache.thrift.TFieldIdEnum {
    JOB_ID((short)1, "jobId"),
    TASK_ID((short)2, "taskId"),
    JOB_DESCRIPTION((short)3, "jobDescription"),
    CREATION_TIME((short)4, "creationTime"),
    JOB_STATUS((short)5, "jobStatus"),
    COMPUTE_RESOURCE_CONSUMED((short)6, "computeResourceConsumed"),
    JOB_NAME((short)7, "jobName"),
    WORKING_DIR((short)8, "workingDir");

    private static final Map<String, _Fields> byName = new HashMap<String, _Fields>();

    static {
      for (_Fields field : EnumSet.allOf(_Fields.class)) {
        byName.put(field.getFieldName(), field);
      }
    }

    /**
     * Find the _Fields constant that matches fieldId, or null if its not found.
     */
    public static _Fields findByThriftId(int fieldId) {
      switch(fieldId) {
        case 1: // JOB_ID
          return JOB_ID;
        case 2: // TASK_ID
          return TASK_ID;
        case 3: // JOB_DESCRIPTION
          return JOB_DESCRIPTION;
        case 4: // CREATION_TIME
          return CREATION_TIME;
        case 5: // JOB_STATUS
          return JOB_STATUS;
        case 6: // COMPUTE_RESOURCE_CONSUMED
          return COMPUTE_RESOURCE_CONSUMED;
        case 7: // JOB_NAME
          return JOB_NAME;
        case 8: // WORKING_DIR
          return WORKING_DIR;
        default:
          return null;
      }
    }

    /**
     * Find the _Fields constant that matches fieldId, throwing an exception
     * if it is not found.
     */
    public static _Fields findByThriftIdOrThrow(int fieldId) {
      _Fields fields = findByThriftId(fieldId);
      if (fields == null) throw new IllegalArgumentException("Field " + fieldId + " doesn't exist!");
      return fields;
    }

    /**
     * Find the _Fields constant that matches name, or null if its not found.
     */
    public static _Fields findByName(String name) {
      return byName.get(name);
    }

    private final short _thriftId;
    private final String _fieldName;

    _Fields(short thriftId, String fieldName) {
      _thriftId = thriftId;
      _fieldName = fieldName;
    }

    public short getThriftFieldId() {
      return _thriftId;
    }

    public String getFieldName() {
      return _fieldName;
    }
  }

  // isset id assignments
  private static final int __CREATIONTIME_ISSET_ID = 0;
  private byte __isset_bitfield = 0;
  private static final _Fields optionals[] = {_Fields.CREATION_TIME,_Fields.JOB_STATUS,_Fields.COMPUTE_RESOURCE_CONSUMED,_Fields.JOB_NAME,_Fields.WORKING_DIR};
  public static final Map<_Fields, org.apache.thrift.meta_data.FieldMetaData> metaDataMap;
  static {
    Map<_Fields, org.apache.thrift.meta_data.FieldMetaData> tmpMap = new EnumMap<_Fields, org.apache.thrift.meta_data.FieldMetaData>(_Fields.class);
    tmpMap.put(_Fields.JOB_ID, new org.apache.thrift.meta_data.FieldMetaData("jobId", org.apache.thrift.TFieldRequirementType.REQUIRED, 
        new org.apache.thrift.meta_data.FieldValueMetaData(org.apache.thrift.protocol.TType.STRING)));
    tmpMap.put(_Fields.TASK_ID, new org.apache.thrift.meta_data.FieldMetaData("taskId", org.apache.thrift.TFieldRequirementType.REQUIRED, 
        new org.apache.thrift.meta_data.FieldValueMetaData(org.apache.thrift.protocol.TType.STRING)));
    tmpMap.put(_Fields.JOB_DESCRIPTION, new org.apache.thrift.meta_data.FieldMetaData("jobDescription", org.apache.thrift.TFieldRequirementType.REQUIRED, 
        new org.apache.thrift.meta_data.FieldValueMetaData(org.apache.thrift.protocol.TType.STRING)));
    tmpMap.put(_Fields.CREATION_TIME, new org.apache.thrift.meta_data.FieldMetaData("creationTime", org.apache.thrift.TFieldRequirementType.OPTIONAL, 
        new org.apache.thrift.meta_data.FieldValueMetaData(org.apache.thrift.protocol.TType.I64)));
    tmpMap.put(_Fields.JOB_STATUS, new org.apache.thrift.meta_data.FieldMetaData("jobStatus", org.apache.thrift.TFieldRequirementType.OPTIONAL, 
        new org.apache.thrift.meta_data.StructMetaData(org.apache.thrift.protocol.TType.STRUCT, org.apache.airavata.model.status.JobStatus.class)));
    tmpMap.put(_Fields.COMPUTE_RESOURCE_CONSUMED, new org.apache.thrift.meta_data.FieldMetaData("computeResourceConsumed", org.apache.thrift.TFieldRequirementType.OPTIONAL, 
        new org.apache.thrift.meta_data.FieldValueMetaData(org.apache.thrift.protocol.TType.STRING)));
    tmpMap.put(_Fields.JOB_NAME, new org.apache.thrift.meta_data.FieldMetaData("jobName", org.apache.thrift.TFieldRequirementType.OPTIONAL, 
        new org.apache.thrift.meta_data.FieldValueMetaData(org.apache.thrift.protocol.TType.STRING)));
    tmpMap.put(_Fields.WORKING_DIR, new org.apache.thrift.meta_data.FieldMetaData("workingDir", org.apache.thrift.TFieldRequirementType.OPTIONAL, 
        new org.apache.thrift.meta_data.FieldValueMetaData(org.apache.thrift.protocol.TType.STRING)));
    metaDataMap = Collections.unmodifiableMap(tmpMap);
    org.apache.thrift.meta_data.FieldMetaData.addStructMetaDataMap(JobModel.class, metaDataMap);
  }

  public JobModel() {
  }

  public JobModel(
    String jobId,
    String taskId,
    String jobDescription)
  {
    this();
    this.jobId = jobId;
    this.taskId = taskId;
    this.jobDescription = jobDescription;
  }

  /**
   * Performs a deep copy on <i>other</i>.
   */
  public JobModel(JobModel other) {
    __isset_bitfield = other.__isset_bitfield;
    if (other.isSetJobId()) {
      this.jobId = other.jobId;
    }
    if (other.isSetTaskId()) {
      this.taskId = other.taskId;
    }
    if (other.isSetJobDescription()) {
      this.jobDescription = other.jobDescription;
    }
    this.creationTime = other.creationTime;
    if (other.isSetJobStatus()) {
      this.jobStatus = new org.apache.airavata.model.status.JobStatus(other.jobStatus);
    }
    if (other.isSetComputeResourceConsumed()) {
      this.computeResourceConsumed = other.computeResourceConsumed;
    }
    if (other.isSetJobName()) {
      this.jobName = other.jobName;
    }
    if (other.isSetWorkingDir()) {
      this.workingDir = other.workingDir;
    }
  }

  public JobModel deepCopy() {
    return new JobModel(this);
  }

  @Override
  public void clear() {
    this.jobId = null;
    this.taskId = null;
    this.jobDescription = null;
    setCreationTimeIsSet(false);
    this.creationTime = 0;
    this.jobStatus = null;
    this.computeResourceConsumed = null;
    this.jobName = null;
    this.workingDir = null;
  }

  public String getJobId() {
    return this.jobId;
  }

  public void setJobId(String jobId) {
    this.jobId = jobId;
  }

  public void unsetJobId() {
    this.jobId = null;
  }

  /** Returns true if field jobId is set (has been assigned a value) and false otherwise */
  public boolean isSetJobId() {
    return this.jobId != null;
  }

  public void setJobIdIsSet(boolean value) {
    if (!value) {
      this.jobId = null;
    }
  }

  public String getTaskId() {
    return this.taskId;
  }

  public void setTaskId(String taskId) {
    this.taskId = taskId;
  }

  public void unsetTaskId() {
    this.taskId = null;
  }

  /** Returns true if field taskId is set (has been assigned a value) and false otherwise */
  public boolean isSetTaskId() {
    return this.taskId != null;
  }

  public void setTaskIdIsSet(boolean value) {
    if (!value) {
      this.taskId = null;
    }
  }

  public String getJobDescription() {
    return this.jobDescription;
  }

  public void setJobDescription(String jobDescription) {
    this.jobDescription = jobDescription;
  }

  public void unsetJobDescription() {
    this.jobDescription = null;
  }

  /** Returns true if field jobDescription is set (has been assigned a value) and false otherwise */
  public boolean isSetJobDescription() {
    return this.jobDescription != null;
  }

  public void setJobDescriptionIsSet(boolean value) {
    if (!value) {
      this.jobDescription = null;
    }
  }

  public long getCreationTime() {
    return this.creationTime;
  }

  public void setCreationTime(long creationTime) {
    this.creationTime = creationTime;
    setCreationTimeIsSet(true);
  }

  public void unsetCreationTime() {
    __isset_bitfield = EncodingUtils.clearBit(__isset_bitfield, __CREATIONTIME_ISSET_ID);
  }

  /** Returns true if field creationTime is set (has been assigned a value) and false otherwise */
  public boolean isSetCreationTime() {
    return EncodingUtils.testBit(__isset_bitfield, __CREATIONTIME_ISSET_ID);
  }

  public void setCreationTimeIsSet(boolean value) {
    __isset_bitfield = EncodingUtils.setBit(__isset_bitfield, __CREATIONTIME_ISSET_ID, value);
  }

  public org.apache.airavata.model.status.JobStatus getJobStatus() {
    return this.jobStatus;
  }

  public void setJobStatus(org.apache.airavata.model.status.JobStatus jobStatus) {
    this.jobStatus = jobStatus;
  }

  public void unsetJobStatus() {
    this.jobStatus = null;
  }

  /** Returns true if field jobStatus is set (has been assigned a value) and false otherwise */
  public boolean isSetJobStatus() {
    return this.jobStatus != null;
  }

  public void setJobStatusIsSet(boolean value) {
    if (!value) {
      this.jobStatus = null;
    }
  }

  public String getComputeResourceConsumed() {
    return this.computeResourceConsumed;
  }

  public void setComputeResourceConsumed(String computeResourceConsumed) {
    this.computeResourceConsumed = computeResourceConsumed;
  }

  public void unsetComputeResourceConsumed() {
    this.computeResourceConsumed = null;
  }

  /** Returns true if field computeResourceConsumed is set (has been assigned a value) and false otherwise */
  public boolean isSetComputeResourceConsumed() {
    return this.computeResourceConsumed != null;
  }

  public void setComputeResourceConsumedIsSet(boolean value) {
    if (!value) {
      this.computeResourceConsumed = null;
    }
  }

  public String getJobName() {
    return this.jobName;
  }

  public void setJobName(String jobName) {
    this.jobName = jobName;
  }

  public void unsetJobName() {
    this.jobName = null;
  }

  /** Returns true if field jobName is set (has been assigned a value) and false otherwise */
  public boolean isSetJobName() {
    return this.jobName != null;
  }

  public void setJobNameIsSet(boolean value) {
    if (!value) {
      this.jobName = null;
    }
  }

  public String getWorkingDir() {
    return this.workingDir;
  }

  public void setWorkingDir(String workingDir) {
    this.workingDir = workingDir;
  }

  public void unsetWorkingDir() {
    this.workingDir = null;
  }

  /** Returns true if field workingDir is set (has been assigned a value) and false otherwise */
  public boolean isSetWorkingDir() {
    return this.workingDir != null;
  }

  public void setWorkingDirIsSet(boolean value) {
    if (!value) {
      this.workingDir = null;
    }
  }

  public void setFieldValue(_Fields field, Object value) {
    switch (field) {
    case JOB_ID:
      if (value == null) {
        unsetJobId();
      } else {
        setJobId((String)value);
      }
      break;

    case TASK_ID:
      if (value == null) {
        unsetTaskId();
      } else {
        setTaskId((String)value);
      }
      break;

    case JOB_DESCRIPTION:
      if (value == null) {
        unsetJobDescription();
      } else {
        setJobDescription((String)value);
      }
      break;

    case CREATION_TIME:
      if (value == null) {
        unsetCreationTime();
      } else {
        setCreationTime((Long)value);
      }
      break;

    case JOB_STATUS:
      if (value == null) {
        unsetJobStatus();
      } else {
        setJobStatus((org.apache.airavata.model.status.JobStatus)value);
      }
      break;

    case COMPUTE_RESOURCE_CONSUMED:
      if (value == null) {
        unsetComputeResourceConsumed();
      } else {
        setComputeResourceConsumed((String)value);
      }
      break;

    case JOB_NAME:
      if (value == null) {
        unsetJobName();
      } else {
        setJobName((String)value);
      }
      break;

    case WORKING_DIR:
      if (value == null) {
        unsetWorkingDir();
      } else {
        setWorkingDir((String)value);
      }
      break;

    }
  }

  public Object getFieldValue(_Fields field) {
    switch (field) {
    case JOB_ID:
      return getJobId();

    case TASK_ID:
      return getTaskId();

    case JOB_DESCRIPTION:
      return getJobDescription();

    case CREATION_TIME:
      return Long.valueOf(getCreationTime());

    case JOB_STATUS:
      return getJobStatus();

    case COMPUTE_RESOURCE_CONSUMED:
      return getComputeResourceConsumed();

    case JOB_NAME:
      return getJobName();

    case WORKING_DIR:
      return getWorkingDir();

    }
    throw new IllegalStateException();
  }

  /** Returns true if field corresponding to fieldID is set (has been assigned a value) and false otherwise */
  public boolean isSet(_Fields field) {
    if (field == null) {
      throw new IllegalArgumentException();
    }

    switch (field) {
    case JOB_ID:
      return isSetJobId();
    case TASK_ID:
      return isSetTaskId();
    case JOB_DESCRIPTION:
      return isSetJobDescription();
    case CREATION_TIME:
      return isSetCreationTime();
    case JOB_STATUS:
      return isSetJobStatus();
    case COMPUTE_RESOURCE_CONSUMED:
      return isSetComputeResourceConsumed();
    case JOB_NAME:
      return isSetJobName();
    case WORKING_DIR:
      return isSetWorkingDir();
    }
    throw new IllegalStateException();
  }

  @Override
  public boolean equals(Object that) {
    if (that == null)
      return false;
    if (that instanceof JobModel)
      return this.equals((JobModel)that);
    return false;
  }

  public boolean equals(JobModel that) {
    if (that == null)
      return false;

    boolean this_present_jobId = true && this.isSetJobId();
    boolean that_present_jobId = true && that.isSetJobId();
    if (this_present_jobId || that_present_jobId) {
      if (!(this_present_jobId && that_present_jobId))
        return false;
      if (!this.jobId.equals(that.jobId))
        return false;
    }

    boolean this_present_taskId = true && this.isSetTaskId();
    boolean that_present_taskId = true && that.isSetTaskId();
    if (this_present_taskId || that_present_taskId) {
      if (!(this_present_taskId && that_present_taskId))
        return false;
      if (!this.taskId.equals(that.taskId))
        return false;
    }

    boolean this_present_jobDescription = true && this.isSetJobDescription();
    boolean that_present_jobDescription = true && that.isSetJobDescription();
    if (this_present_jobDescription || that_present_jobDescription) {
      if (!(this_present_jobDescription && that_present_jobDescription))
        return false;
      if (!this.jobDescription.equals(that.jobDescription))
        return false;
    }

    boolean this_present_creationTime = true && this.isSetCreationTime();
    boolean that_present_creationTime = true && that.isSetCreationTime();
    if (this_present_creationTime || that_present_creationTime) {
      if (!(this_present_creationTime && that_present_creationTime))
        return false;
      if (this.creationTime != that.creationTime)
        return false;
    }

    boolean this_present_jobStatus = true && this.isSetJobStatus();
    boolean that_present_jobStatus = true && that.isSetJobStatus();
    if (this_present_jobStatus || that_present_jobStatus) {
      if (!(this_present_jobStatus && that_present_jobStatus))
        return false;
      if (!this.jobStatus.equals(that.jobStatus))
        return false;
    }

    boolean this_present_computeResourceConsumed = true && this.isSetComputeResourceConsumed();
    boolean that_present_computeResourceConsumed = true && that.isSetComputeResourceConsumed();
    if (this_present_computeResourceConsumed || that_present_computeResourceConsumed) {
      if (!(this_present_computeResourceConsumed && that_present_computeResourceConsumed))
        return false;
      if (!this.computeResourceConsumed.equals(that.computeResourceConsumed))
        return false;
    }

    boolean this_present_jobName = true && this.isSetJobName();
    boolean that_present_jobName = true && that.isSetJobName();
    if (this_present_jobName || that_present_jobName) {
      if (!(this_present_jobName && that_present_jobName))
        return false;
      if (!this.jobName.equals(that.jobName))
        return false;
    }

    boolean this_present_workingDir = true && this.isSetWorkingDir();
    boolean that_present_workingDir = true && that.isSetWorkingDir();
    if (this_present_workingDir || that_present_workingDir) {
      if (!(this_present_workingDir && that_present_workingDir))
        return false;
      if (!this.workingDir.equals(that.workingDir))
        return false;
    }

    return true;
  }

  @Override
  public int hashCode() {
    List<Object> list = new ArrayList<Object>();

    boolean present_jobId = true && (isSetJobId());
    list.add(present_jobId);
    if (present_jobId)
      list.add(jobId);

    boolean present_taskId = true && (isSetTaskId());
    list.add(present_taskId);
    if (present_taskId)
      list.add(taskId);

    boolean present_jobDescription = true && (isSetJobDescription());
    list.add(present_jobDescription);
    if (present_jobDescription)
      list.add(jobDescription);

    boolean present_creationTime = true && (isSetCreationTime());
    list.add(present_creationTime);
    if (present_creationTime)
      list.add(creationTime);

    boolean present_jobStatus = true && (isSetJobStatus());
    list.add(present_jobStatus);
    if (present_jobStatus)
      list.add(jobStatus);

    boolean present_computeResourceConsumed = true && (isSetComputeResourceConsumed());
    list.add(present_computeResourceConsumed);
    if (present_computeResourceConsumed)
      list.add(computeResourceConsumed);

    boolean present_jobName = true && (isSetJobName());
    list.add(present_jobName);
    if (present_jobName)
      list.add(jobName);

    boolean present_workingDir = true && (isSetWorkingDir());
    list.add(present_workingDir);
    if (present_workingDir)
      list.add(workingDir);

    return list.hashCode();
  }

  @Override
  public int compareTo(JobModel other) {
    if (!getClass().equals(other.getClass())) {
      return getClass().getName().compareTo(other.getClass().getName());
    }

    int lastComparison = 0;

    lastComparison = Boolean.valueOf(isSetJobId()).compareTo(other.isSetJobId());
    if (lastComparison != 0) {
      return lastComparison;
    }
    if (isSetJobId()) {
      lastComparison = org.apache.thrift.TBaseHelper.compareTo(this.jobId, other.jobId);
      if (lastComparison != 0) {
        return lastComparison;
      }
    }
    lastComparison = Boolean.valueOf(isSetTaskId()).compareTo(other.isSetTaskId());
    if (lastComparison != 0) {
      return lastComparison;
    }
    if (isSetTaskId()) {
      lastComparison = org.apache.thrift.TBaseHelper.compareTo(this.taskId, other.taskId);
      if (lastComparison != 0) {
        return lastComparison;
      }
    }
    lastComparison = Boolean.valueOf(isSetJobDescription()).compareTo(other.isSetJobDescription());
    if (lastComparison != 0) {
      return lastComparison;
    }
    if (isSetJobDescription()) {
      lastComparison = org.apache.thrift.TBaseHelper.compareTo(this.jobDescription, other.jobDescription);
      if (lastComparison != 0) {
        return lastComparison;
      }
    }
    lastComparison = Boolean.valueOf(isSetCreationTime()).compareTo(other.isSetCreationTime());
    if (lastComparison != 0) {
      return lastComparison;
    }
    if (isSetCreationTime()) {
      lastComparison = org.apache.thrift.TBaseHelper.compareTo(this.creationTime, other.creationTime);
      if (lastComparison != 0) {
        return lastComparison;
      }
    }
    lastComparison = Boolean.valueOf(isSetJobStatus()).compareTo(other.isSetJobStatus());
    if (lastComparison != 0) {
      return lastComparison;
    }
    if (isSetJobStatus()) {
      lastComparison = org.apache.thrift.TBaseHelper.compareTo(this.jobStatus, other.jobStatus);
      if (lastComparison != 0) {
        return lastComparison;
      }
    }
    lastComparison = Boolean.valueOf(isSetComputeResourceConsumed()).compareTo(other.isSetComputeResourceConsumed());
    if (lastComparison != 0) {
      return lastComparison;
    }
    if (isSetComputeResourceConsumed()) {
      lastComparison = org.apache.thrift.TBaseHelper.compareTo(this.computeResourceConsumed, other.computeResourceConsumed);
      if (lastComparison != 0) {
        return lastComparison;
      }
    }
    lastComparison = Boolean.valueOf(isSetJobName()).compareTo(other.isSetJobName());
    if (lastComparison != 0) {
      return lastComparison;
    }
    if (isSetJobName()) {
      lastComparison = org.apache.thrift.TBaseHelper.compareTo(this.jobName, other.jobName);
      if (lastComparison != 0) {
        return lastComparison;
      }
    }
    lastComparison = Boolean.valueOf(isSetWorkingDir()).compareTo(other.isSetWorkingDir());
    if (lastComparison != 0) {
      return lastComparison;
    }
    if (isSetWorkingDir()) {
      lastComparison = org.apache.thrift.TBaseHelper.compareTo(this.workingDir, other.workingDir);
      if (lastComparison != 0) {
        return lastComparison;
      }
    }
    return 0;
  }

  public _Fields fieldForId(int fieldId) {
    return _Fields.findByThriftId(fieldId);
  }

  public void read(org.apache.thrift.protocol.TProtocol iprot) throws org.apache.thrift.TException {
    schemes.get(iprot.getScheme()).getScheme().read(iprot, this);
  }

  public void write(org.apache.thrift.protocol.TProtocol oprot) throws org.apache.thrift.TException {
    schemes.get(oprot.getScheme()).getScheme().write(oprot, this);
  }

  @Override
  public String toString() {
    StringBuilder sb = new StringBuilder("JobModel(");
    boolean first = true;

    sb.append("jobId:");
    if (this.jobId == null) {
      sb.append("null");
    } else {
      sb.append(this.jobId);
    }
    first = false;
    if (!first) sb.append(", ");
    sb.append("taskId:");
    if (this.taskId == null) {
      sb.append("null");
    } else {
      sb.append(this.taskId);
    }
    first = false;
    if (!first) sb.append(", ");
    sb.append("jobDescription:");
    if (this.jobDescription == null) {
      sb.append("null");
    } else {
      sb.append(this.jobDescription);
    }
    first = false;
    if (isSetCreationTime()) {
      if (!first) sb.append(", ");
      sb.append("creationTime:");
      sb.append(this.creationTime);
      first = false;
    }
    if (isSetJobStatus()) {
      if (!first) sb.append(", ");
      sb.append("jobStatus:");
      if (this.jobStatus == null) {
        sb.append("null");
      } else {
        sb.append(this.jobStatus);
      }
      first = false;
    }
    if (isSetComputeResourceConsumed()) {
      if (!first) sb.append(", ");
      sb.append("computeResourceConsumed:");
      if (this.computeResourceConsumed == null) {
        sb.append("null");
      } else {
        sb.append(this.computeResourceConsumed);
      }
      first = false;
    }
    if (isSetJobName()) {
      if (!first) sb.append(", ");
      sb.append("jobName:");
      if (this.jobName == null) {
        sb.append("null");
      } else {
        sb.append(this.jobName);
      }
      first = false;
    }
    if (isSetWorkingDir()) {
      if (!first) sb.append(", ");
      sb.append("workingDir:");
      if (this.workingDir == null) {
        sb.append("null");
      } else {
        sb.append(this.workingDir);
      }
      first = false;
    }
    sb.append(")");
    return sb.toString();
  }

  public void validate() throws org.apache.thrift.TException {
    // check for required fields
    if (!isSetJobId()) {
      throw new org.apache.thrift.protocol.TProtocolException("Required field 'jobId' is unset! Struct:" + toString());
    }

    if (!isSetTaskId()) {
      throw new org.apache.thrift.protocol.TProtocolException("Required field 'taskId' is unset! Struct:" + toString());
    }

    if (!isSetJobDescription()) {
      throw new org.apache.thrift.protocol.TProtocolException("Required field 'jobDescription' is unset! Struct:" + toString());
    }

    // check for sub-struct validity
    if (jobStatus != null) {
      jobStatus.validate();
    }
  }

  private void writeObject(java.io.ObjectOutputStream out) throws java.io.IOException {
    try {
      write(new org.apache.thrift.protocol.TCompactProtocol(new org.apache.thrift.transport.TIOStreamTransport(out)));
    } catch (org.apache.thrift.TException te) {
      throw new java.io.IOException(te);
    }
  }

  private void readObject(java.io.ObjectInputStream in) throws java.io.IOException, ClassNotFoundException {
    try {
      // it doesn't seem like you should have to do this, but java serialization is wacky, and doesn't call the default constructor.
      __isset_bitfield = 0;
      read(new org.apache.thrift.protocol.TCompactProtocol(new org.apache.thrift.transport.TIOStreamTransport(in)));
    } catch (org.apache.thrift.TException te) {
      throw new java.io.IOException(te);
    }
  }

  private static class JobModelStandardSchemeFactory implements SchemeFactory {
    public JobModelStandardScheme getScheme() {
      return new JobModelStandardScheme();
    }
  }

  private static class JobModelStandardScheme extends StandardScheme<JobModel> {

    public void read(org.apache.thrift.protocol.TProtocol iprot, JobModel struct) throws org.apache.thrift.TException {
      org.apache.thrift.protocol.TField schemeField;
      iprot.readStructBegin();
      while (true)
      {
        schemeField = iprot.readFieldBegin();
        if (schemeField.type == org.apache.thrift.protocol.TType.STOP) { 
          break;
        }
        switch (schemeField.id) {
          case 1: // JOB_ID
            if (schemeField.type == org.apache.thrift.protocol.TType.STRING) {
              struct.jobId = iprot.readString();
              struct.setJobIdIsSet(true);
            } else { 
              org.apache.thrift.protocol.TProtocolUtil.skip(iprot, schemeField.type);
            }
            break;
          case 2: // TASK_ID
            if (schemeField.type == org.apache.thrift.protocol.TType.STRING) {
              struct.taskId = iprot.readString();
              struct.setTaskIdIsSet(true);
            } else { 
              org.apache.thrift.protocol.TProtocolUtil.skip(iprot, schemeField.type);
            }
            break;
          case 3: // JOB_DESCRIPTION
            if (schemeField.type == org.apache.thrift.protocol.TType.STRING) {
              struct.jobDescription = iprot.readString();
              struct.setJobDescriptionIsSet(true);
            } else { 
              org.apache.thrift.protocol.TProtocolUtil.skip(iprot, schemeField.type);
            }
            break;
          case 4: // CREATION_TIME
            if (schemeField.type == org.apache.thrift.protocol.TType.I64) {
              struct.creationTime = iprot.readI64();
              struct.setCreationTimeIsSet(true);
            } else { 
              org.apache.thrift.protocol.TProtocolUtil.skip(iprot, schemeField.type);
            }
            break;
          case 5: // JOB_STATUS
            if (schemeField.type == org.apache.thrift.protocol.TType.STRUCT) {
              struct.jobStatus = new org.apache.airavata.model.status.JobStatus();
              struct.jobStatus.read(iprot);
              struct.setJobStatusIsSet(true);
            } else { 
              org.apache.thrift.protocol.TProtocolUtil.skip(iprot, schemeField.type);
            }
            break;
          case 6: // COMPUTE_RESOURCE_CONSUMED
            if (schemeField.type == org.apache.thrift.protocol.TType.STRING) {
              struct.computeResourceConsumed = iprot.readString();
              struct.setComputeResourceConsumedIsSet(true);
            } else { 
              org.apache.thrift.protocol.TProtocolUtil.skip(iprot, schemeField.type);
            }
            break;
          case 7: // JOB_NAME
            if (schemeField.type == org.apache.thrift.protocol.TType.STRING) {
              struct.jobName = iprot.readString();
              struct.setJobNameIsSet(true);
            } else { 
              org.apache.thrift.protocol.TProtocolUtil.skip(iprot, schemeField.type);
            }
            break;
          case 8: // WORKING_DIR
            if (schemeField.type == org.apache.thrift.protocol.TType.STRING) {
              struct.workingDir = iprot.readString();
              struct.setWorkingDirIsSet(true);
            } else { 
              org.apache.thrift.protocol.TProtocolUtil.skip(iprot, schemeField.type);
            }
            break;
          default:
            org.apache.thrift.protocol.TProtocolUtil.skip(iprot, schemeField.type);
        }
        iprot.readFieldEnd();
      }
      iprot.readStructEnd();
      struct.validate();
    }

    public void write(org.apache.thrift.protocol.TProtocol oprot, JobModel struct) throws org.apache.thrift.TException {
      struct.validate();

      oprot.writeStructBegin(STRUCT_DESC);
      if (struct.jobId != null) {
        oprot.writeFieldBegin(JOB_ID_FIELD_DESC);
        oprot.writeString(struct.jobId);
        oprot.writeFieldEnd();
      }
      if (struct.taskId != null) {
        oprot.writeFieldBegin(TASK_ID_FIELD_DESC);
        oprot.writeString(struct.taskId);
        oprot.writeFieldEnd();
      }
      if (struct.jobDescription != null) {
        oprot.writeFieldBegin(JOB_DESCRIPTION_FIELD_DESC);
        oprot.writeString(struct.jobDescription);
        oprot.writeFieldEnd();
      }
      if (struct.isSetCreationTime()) {
        oprot.writeFieldBegin(CREATION_TIME_FIELD_DESC);
        oprot.writeI64(struct.creationTime);
        oprot.writeFieldEnd();
      }
      if (struct.jobStatus != null) {
        if (struct.isSetJobStatus()) {
          oprot.writeFieldBegin(JOB_STATUS_FIELD_DESC);
          struct.jobStatus.write(oprot);
          oprot.writeFieldEnd();
        }
      }
      if (struct.computeResourceConsumed != null) {
        if (struct.isSetComputeResourceConsumed()) {
          oprot.writeFieldBegin(COMPUTE_RESOURCE_CONSUMED_FIELD_DESC);
          oprot.writeString(struct.computeResourceConsumed);
          oprot.writeFieldEnd();
        }
      }
      if (struct.jobName != null) {
        if (struct.isSetJobName()) {
          oprot.writeFieldBegin(JOB_NAME_FIELD_DESC);
          oprot.writeString(struct.jobName);
          oprot.writeFieldEnd();
        }
      }
      if (struct.workingDir != null) {
        if (struct.isSetWorkingDir()) {
          oprot.writeFieldBegin(WORKING_DIR_FIELD_DESC);
          oprot.writeString(struct.workingDir);
          oprot.writeFieldEnd();
        }
      }
      oprot.writeFieldStop();
      oprot.writeStructEnd();
    }

  }

  private static class JobModelTupleSchemeFactory implements SchemeFactory {
    public JobModelTupleScheme getScheme() {
      return new JobModelTupleScheme();
    }
  }

  private static class JobModelTupleScheme extends TupleScheme<JobModel> {

    @Override
    public void write(org.apache.thrift.protocol.TProtocol prot, JobModel struct) throws org.apache.thrift.TException {
      TTupleProtocol oprot = (TTupleProtocol) prot;
      oprot.writeString(struct.jobId);
      oprot.writeString(struct.taskId);
      oprot.writeString(struct.jobDescription);
      BitSet optionals = new BitSet();
      if (struct.isSetCreationTime()) {
        optionals.set(0);
      }
      if (struct.isSetJobStatus()) {
        optionals.set(1);
      }
      if (struct.isSetComputeResourceConsumed()) {
        optionals.set(2);
      }
      if (struct.isSetJobName()) {
        optionals.set(3);
      }
      if (struct.isSetWorkingDir()) {
        optionals.set(4);
      }
      oprot.writeBitSet(optionals, 5);
      if (struct.isSetCreationTime()) {
        oprot.writeI64(struct.creationTime);
      }
      if (struct.isSetJobStatus()) {
        struct.jobStatus.write(oprot);
      }
      if (struct.isSetComputeResourceConsumed()) {
        oprot.writeString(struct.computeResourceConsumed);
      }
      if (struct.isSetJobName()) {
        oprot.writeString(struct.jobName);
      }
      if (struct.isSetWorkingDir()) {
        oprot.writeString(struct.workingDir);
      }
    }

    @Override
    public void read(org.apache.thrift.protocol.TProtocol prot, JobModel struct) throws org.apache.thrift.TException {
      TTupleProtocol iprot = (TTupleProtocol) prot;
      struct.jobId = iprot.readString();
      struct.setJobIdIsSet(true);
      struct.taskId = iprot.readString();
      struct.setTaskIdIsSet(true);
      struct.jobDescription = iprot.readString();
      struct.setJobDescriptionIsSet(true);
      BitSet incoming = iprot.readBitSet(5);
      if (incoming.get(0)) {
        struct.creationTime = iprot.readI64();
        struct.setCreationTimeIsSet(true);
      }
      if (incoming.get(1)) {
        struct.jobStatus = new org.apache.airavata.model.status.JobStatus();
        struct.jobStatus.read(iprot);
        struct.setJobStatusIsSet(true);
      }
      if (incoming.get(2)) {
        struct.computeResourceConsumed = iprot.readString();
        struct.setComputeResourceConsumedIsSet(true);
      }
      if (incoming.get(3)) {
        struct.jobName = iprot.readString();
        struct.setJobNameIsSet(true);
      }
      if (incoming.get(4)) {
        struct.workingDir = iprot.readString();
        struct.setWorkingDirIsSet(true);
      }
    }
  }

}

