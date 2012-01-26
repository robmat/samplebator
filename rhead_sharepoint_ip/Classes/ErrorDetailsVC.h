//
//  ErrorDetailsVC.h
//  rhead_sharepoint_ip
//
//  Created by Robert Batorowski on 12-01-18.
//  Copyright (c) 2012 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>

@interface ErrorDetailsVC : UIViewController {
    
    IBOutlet UITextView* text;
    NSString* errorDetails;
}

@property(nonatomic, retain) IBOutlet UITextView* text;
@property(nonatomic, retain) NSString* errorDetails;

@end
