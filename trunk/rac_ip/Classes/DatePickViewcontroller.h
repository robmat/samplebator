//
//  DatePickViewcontroller.h
//  rac_ip
//
//  Created by User on 3/24/11.
//  Copyright 2011 __MyCompanyName__. All rights reserved.
//

#import <UIKit/UIKit.h>


@interface DatePickViewcontroller : UIViewController {
	IBOutlet UIDatePicker* datePicker;
}

- (IBAction) pickAction: (id) sender;

@end
