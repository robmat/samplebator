//
//  HttpRequestWrapper.h
//  bcm_ip
//
//  Created by User on 3/8/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>
#import "ASIFormDataRequest.h"

@interface HttpRequestWrapper : NSObject {
	NSObject* delegate;
	ASIFormDataRequest* request;
}

- (HttpRequestWrapper*) initWithDelegate: (NSObject*) deleg;
- (void) makeRequestWithParams: (NSDictionary*) params;

@end
