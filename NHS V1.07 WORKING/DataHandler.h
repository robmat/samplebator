//
//  DataHandler.h
//  PushViewControllerAnimated
//
//  Created by User on 1/12/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <Foundation/Foundation.h>


@interface DataHandler : NSObject {
	NSArray* data;
}
- (id) init;
- (NSMutableArray*) getDataByCategory:(NSString*) category;
@end
